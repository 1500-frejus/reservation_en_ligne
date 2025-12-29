<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;

class ConfirmBookings extends Command
{
    protected $signature = 'bookings:confirm {minutes=10}';
    protected $description = 'Confirme automatiquement les réservations pending plus vieilles que X minutes';

    public function handle()
    {
        $minutes = $this->argument('minutes');
        $cutoff = now()->subMinutes($minutes);

        $pending = Booking::where('status', 'pending')->where('created_at', '<', $cutoff)->get();
        foreach ($pending as $b) {
            $b->status = 'confirmed';
            // generate qr_token if missing
            if (! $b->qr_token) $b->qr_token = \Illuminate\Support\Str::random(40);
            $b->save();

            // send confirmation email (silently)
            if (class_exists(BookingConfirmedMail::class)) {
                try {
                    Mail::to($b->passenger_email)->send(new BookingConfirmedMail($b));
                } catch (\Throwable $e) {
                    // ignore send errors in command
                }
            }
        }

        $this->info('Confirmed '.$pending->count().' reservations.');
        return 0;
    }
}
