<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet #{{ $booking->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 20px; }
        .section h5 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .row { display: flex; margin-bottom: 10px; }
        .col { flex: 1; padding: 0 10px; }
        .qr-code { text-align: center; margin-top: 20px; }
        .qr-code img { max-width: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réservation Confirmée</h1>
        <p>Billet #{{ $booking->id }}</p>
        <p>Réservé le {{ $booking->created_at->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="section">
        <h5>Informations du Passager</h5>
        <div class="row">
            <div class="col">
                <strong>Nom:</strong> {{ $booking->passenger_name }}
            </div>
            <div class="col">
                <strong>Email:</strong> {{ $booking->passenger_email }}
            </div>
        </div>
    </div>

    <div class="section">
        <h5>Détails du Trajet</h5>
        <div class="row">
            <div class="col">
                <strong>Départ:</strong> {{ $booking->route->depart }}
            </div>
            <div class="col">
                <strong>Arrivée:</strong> {{ $booking->route->arrivee }}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <strong>Horaire:</strong> {{ $booking->route->horaire->format('H:i') }}
            </div>
            <div class="col">
                <strong>Durée:</strong> {{ $booking->route->duree_minutes }} min
            </div>
        </div>
    </div>

    <div class="section">
        <h5>Statut & Paiement</h5>
        <div class="row">
            <div class="col">
                <strong>Statut:</strong> {{ $booking->status }}
            </div>
            <div class="col">
                <strong>Prix Total:</strong> {{ number_format($booking->route->prix * $booking->seats, 2) }}€ ({{ $booking->seats }} place{{ $booking->seats > 1 ? 's' : '' }})
            </div>
        </div>
    </div>

    <div class="qr-code">
        <h5>QR Code du Billet</h5>
        @if(!empty($qrCodeData))
            <img src="data:image/png;base64,{{ $qrCodeData }}" alt="QR Code" style="width: 200px; height: 200px;">
        @elseif(!empty($qrCodeSvg))
            {{-- Inline SVG (sans déclaration XML) — Dompdf gère l'inline SVG de façon plus robuste que certains data-URIs --}}
            <div style="width:200px; height:200px; display:flex; align-items:center; justify-content:center;">
                {!! $qrCodeSvg !!}
            </div>
        @else
            @if(!empty($qrGridHtml))
                {{-- Grille HTML fallback (sera rendue par Dompdf) --}}
                <div style="width:200px; height:200px; display:flex; align-items:center; justify-content:center;">
                    {!! $qrGridHtml !!}
                </div>
            @else
                <div style="width:200px; height:200px; display:flex; align-items:center; justify-content:center; border:1px solid #ccc;">
                    <small>QR indisponible</small>
                </div>
            @endif
        @endif
        <p>Présentez ce QR code à l'embarquement</p>
    </div>
</body>
</html>