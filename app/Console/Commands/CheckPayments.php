<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les paiements dans la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = \App\Models\Payment::take(5)->get();
        $this->info('Détails des 5 premiers paiements:');

        foreach ($payments as $payment) {
            $this->line("ID: {$payment->id}, Reservation ID: {$payment->reservation_id}, Montant: {$payment->montant}, Mode: {$payment->mode}, Statut: {$payment->statut}");
        }

        // Vérifier les réservations sans paiement
        $bookingsWithoutPayment = \App\Models\Booking::doesntHave('payment')->count();
        $this->info("Réservations sans paiement: {$bookingsWithoutPayment}");

        // Vérifier les paiements sans réservation
        $paymentsWithoutBooking = \App\Models\Payment::whereDoesntHave('booking')->count();
        $this->info("Paiements sans réservation: {$paymentsWithoutBooking}");
    }
}
