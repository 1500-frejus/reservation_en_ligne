<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les réservations dans la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookings = \App\Models\Booking::all();
        $this->info('Nombre total de réservations: ' . $bookings->count());

        if ($bookings->count() > 0) {
            $this->table(
                ['ID', 'User ID', 'Route ID', 'Passenger', 'Status', 'Created At'],
                $bookings->map(function ($booking) {
                    return [
                        $booking->id,
                        $booking->user_id,
                        $booking->route_id,
                        $booking->passenger_name,
                        $booking->status,
                        $booking->created_at->format('Y-m-d H:i:s')
                    ];
                })->toArray()
            );
        } else {
            $this->warn('Aucune réservation trouvée dans la base de données.');
        }
    }
}
