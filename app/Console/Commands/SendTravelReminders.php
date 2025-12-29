<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendTravelReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-travel {--hours=24 : Nombre d\'heures avant le départ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer des rappels de voyage aux passagers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $this->info("Envoi des rappels de voyage pour les départs dans {$hours}h...");

        // Trouver les réservations confirmées dont le départ est dans exactement X heures
        $targetTime = now()->addHours($hours);

        $bookings = Booking::with(['schedule', 'schedule.route'])
            ->where('status', 'confirmed')
            ->whereHas('schedule', function($query) use ($targetTime) {
                $query->whereBetween('departure_at', [
                    $targetTime->copy()->subMinutes(30), // 30 minutes de tolérance
                    $targetTime->copy()->addMinutes(30)
                ]);
            })
            ->get();

        $this->info("Trouvé {$bookings->count()} réservations à rappeler.");

        $sent = 0;
        foreach ($bookings as $booking) {
            try {
                NotificationService::travelReminder($booking);
                $sent++;
                $this->line("Rappel envoyé pour la réservation #{$booking->id}");
            } catch (\Exception $e) {
                $this->error("Erreur pour la réservation #{$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("{$sent} rappels de voyage envoyés avec succès.");
        return Command::SUCCESS;
    }
}
