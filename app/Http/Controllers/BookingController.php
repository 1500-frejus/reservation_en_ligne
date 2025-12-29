<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;
use App\Services\NotificationService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Dompdf\Dompdf;

class BookingController extends Controller
{
    public function create(Request $request, Route $route)
    {
        $date = $request->get('date');
        $passengers = $request->get('passengers', 1);

        // Récupérer les horaires disponibles pour cette route (futurs uniquement)
        $schedules = $route->schedules()->where('departure_at', '>', now())->orderBy('departure_at')->get();

        return view('bookings.create', compact('route', 'date', 'passengers', 'schedules'));
    }

    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour faire une réservation.');
        }

        $data = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'passengers' => 'required|integer|min:1|max:10',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'payment_mode' => 'required|string|in:carte,paypal,especes',
        ]);

        $schedule = Schedule::findOrFail($data['schedule_id']);

        // Vérifier la disponibilité des places
        $bookedSeats = $schedule->bookings->sum('seats');
        if ($schedule->seats_available && ($bookedSeats + $data['passengers']) > $schedule->seats_available) {
            return back()->withErrors(['schedule_id' => 'Pas assez de places disponibles pour cet horaire.']);
        }

        $total = $schedule->price * $data['passengers'];

        // Créer la réservation
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'schedule_id' => $schedule->id,
            'passenger_name' => $data['nom'] . ' ' . $data['prenom'],
            'passenger_email' => $data['email'],
            'seats' => $data['passengers'],
            'status' => 'pending',
            'route_id' => $schedule->route_id, // Garder pour compatibilité
        ]);

        // Créer le paiement
        $booking->payment()->create([
            'montant' => $total,
            'mode' => $data['payment_mode'],
            'statut' => 'paid', // Simulation
        ]);

        // Créer le ticket avec QR
        $qrData = url(route('bookings.show', $booking->id));
        $booking->ticket()->create([
            'qr_code' => $qrData,
            'pdf_url' => null, // À implémenter plus tard
        ]);

        // Créer une notification pour l'utilisateur
        NotificationService::bookingConfirmed($booking);

        // Envoyer email de confirmation
        Mail::to($data['email'])->send(new BookingConfirmedMail($booking));

        return redirect()->route('bookings.show', $booking)->with('success', 'Réservation confirmée ! Un email vous a été envoyé.');
    }

    public function show(Booking $booking)
    {
        $booking->load('route', 'payment', 'ticket');
        return view('bookings.show', compact('booking'));
    }

    public function history()
    {
        $bookings = Booking::with('route', 'payment')->where('user_id', auth()->id())->latest()->get();
        return view('bookings.history', compact('bookings'));
    }

    public function downloadPdf(Booking $booking)
    {
        $booking->load('route', 'payment', 'ticket');

        // Générer le QR code (PNG si possible, sinon SVG en fallback)
        $qrCodeData = null;
        $qrCodeSvg = null;
        $qrGridHtml = null;

        $qrCode = new QrCode($booking->ticket->qr_code);
        $qrCode->setSize(200);

        try {
            // PNG writer (nécessite GD)
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrCodeData = base64_encode($result->getString());
        } catch (\Throwable $e) {
            // Si l'écriture PNG échoue (par ex. GD manquant), on retombe sur SVG
            try {
                $writer = new SvgWriter();
                $result = $writer->write($qrCode);
                // Nettoyer la déclaration XML qui peut gêner l'injection inline
                $qrCodeSvg = preg_replace('/^<\?xml.*?\?>\s*/', '', $result->getString());
                // S'assurer qu'il n'y ait pas d'entités ou d'encodage problématique
                $qrCodeSvg = trim($qrCodeSvg);
                // Si Imagick est disponible, convertir le SVG en PNG pour Dompdf
                if (extension_loaded('imagick')) {
                    try {
                        $imagick = new \Imagick();
                        // Lecture du SVG (blob)
                        $imagick->setBackgroundColor(new \ImagickPixel('transparent'));
                        $imagick->readImageBlob($qrCodeSvg);
                        $imagick->setImageFormat('png24');
                        $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
                        $pngBlob = $imagick->getImageBlob();
                        if (!empty($pngBlob)) {
                            $qrCodeData = base64_encode($pngBlob);
                            // Free resources
                            $imagick->clear();
                            $imagick->destroy();
                        }
                    } catch (\Throwable $e) {
                        logger()->warning('Imagick conversion failed: ' . $e->getMessage());
                    }
                    // Si toujours pas d'image raster disponible, construire une grille HTML (divs) à partir de la matrice
                    if (empty($qrCodeData)) {
                        try {
                            $matrixFactory = new \Endroid\QrCode\Bacon\MatrixFactory();
                            $matrix = $matrixFactory->create($qrCode);
                            $blockCount = $matrix->getBlockCount();
                            // Scale to fit ~200px width
                            $blockSize = max(1, intval(floor(200 / $blockCount)));
                            $outerSize = $matrix->getOuterSize();

                            $gridStyle = 'display:grid;grid-template-columns:repeat('.$blockCount.', '.$blockSize.'px);width:'.($blockCount*$blockSize).'px;line-height:0;';
                            $html = '<div style="'.$gridStyle.'">';
                            for ($r = 0; $r < $blockCount; ++$r) {
                                for ($c = 0; $c < $blockCount; ++$c) {
                                    $val = $matrix->getBlockValue($r, $c);
                                    if ($val === 1) {
                                        $html .= '<div style="width:'.$blockSize.'px;height:'.$blockSize.'px;background:#000;margin:0;padding:0;"></div>';
                                    } else {
                                        $html .= '<div style="width:'.$blockSize.'px;height:'.$blockSize.'px;background:transparent;margin:0;padding:0;"></div>';
                                    }
                                }
                            }
                            $html .= '</div>';
                            $qrGridHtml = $html;
                        } catch (\Throwable $e) {
                            logger()->warning('QR grid fallback failed: ' . $e->getMessage());
                        }
                    }
                }
            } catch (\Throwable $e) {
                // En dernier recours, on laisse les deux null et le template affichera un texte
                // Vous pouvez logger l'erreur si besoin
                logger()->error('QR code generation failed: ' . $e->getMessage());
            }
        }

        $dompdf = new Dompdf();

        $html = view('bookings.pdf', compact('booking', 'qrCodeData', 'qrCodeSvg', 'qrGridHtml'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('billet_' . $booking->id . '.pdf', array('Attachment' => true));
    }
}
