<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationService
{
    /**
     * Envoyer une notification à un utilisateur spécifique
     */
    public static function sendToUser($userId, $type, $content, $sendEmail = false)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'contenu' => $content,
            'statut' => 'unread'
        ]);

        if ($sendEmail && $notification->user) {
            try {
                Mail::to($notification->user->email)->send(new NotificationMail($type, $content));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email notification: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    /**
     * Envoyer une notification à tous les utilisateurs
     */
    public static function sendToAll($type, $content, $sendEmail = false)
    {
        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'contenu' => $content,
                'statut' => 'unread'
            ]);

            $notifications[] = $notification;

            if ($sendEmail) {
                try {
                    Mail::to($user->email)->send(new NotificationMail($type, $content));
                } catch (\Exception $e) {
                    \Log::error('Erreur envoi email notification: ' . $e->getMessage());
                }
            }
        }

        return $notifications;
    }

    /**
     * Notification de confirmation de réservation
     */
    public static function bookingConfirmed($booking)
    {
        $schedule = $booking->schedule;
        $route = $schedule ? $schedule->route : $booking->route;
        $payment = $booking->payment;

        if (!$route) {
            return false;
        }

        $departureTime = $schedule && $schedule->departure_at ? $schedule->departure_at->format('d/m/Y à H:i') : 'date inconnue';
        $amount = $payment ? $payment->montant : 0;

        $content = "Votre réservation #{$booking->id} pour le trajet {$route->depart} → {$route->arrivee} le {$departureTime} a été confirmée. Prix total : {$amount}€.";

        return self::sendToUser($booking->user_id, 'Confirmation de réservation', $content, true);
    }

    /**
     * Notification de rappel de voyage (24h avant)
     */
    public static function travelReminder($booking)
    {
        $schedule = $booking->schedule;
        $route = $schedule ? $schedule->route : $booking->route;

        if (!$route) {
            return false;
        }

        $departureTime = $schedule && $schedule->departure_at ? $schedule->departure_at->format('d/m/Y à H:i') : 'date inconnue';

        $content = "Rappel : Votre voyage {$route->depart} → {$route->arrivee} est prévu demain {$departureTime}. N'oubliez pas votre ticket !";

        return self::sendToUser($booking->user_id, 'Rappel de voyage', $content, true);
    }

    /**
     * Notification d'annulation de réservation
     */
    public static function bookingCancelled($booking)
    {
        $schedule = $booking->schedule;
        $route = $schedule ? $schedule->route : $booking->route;

        if (!$route) {
            return false;
        }

        $departureTime = $schedule && $schedule->departure_at ? $schedule->departure_at->format('d/m/Y à H:i') : 'date inconnue';

        $content = "Votre réservation #{$booking->id} pour le trajet {$route->depart} → {$route->arrivee} le {$departureTime} a été annulée.";

        return self::sendToUser($booking->user_id, 'Annulation de réservation', $content, true);
    }

    /**
     * Notification de modification d'horaire
     */
    public static function scheduleChanged($booking, $oldDateTime, $newDateTime)
    {
        $schedule = $booking->schedule;
        $route = $schedule ? $schedule->route : $booking->route;

        if (!$route) {
            return false;
        }

        $oldTime = $oldDateTime ? $oldDateTime->format('d/m/Y H:i') : 'date inconnue';
        $newTime = $newDateTime ? $newDateTime->format('d/m/Y H:i') : 'date inconnue';

        $content = "Modification d'horaire : Votre réservation #{$booking->id} pour {$route->depart} → {$route->arrivee} a été déplacée du {$oldTime} au {$newTime}.";

        return self::sendToUser($booking->user_id, 'Modification d\'horaire', $content, true);
    }

    /**
     * Notification de promotion
     */
    public static function promotion($title, $content)
    {
        return self::sendToAll('Promotion', $title . ' - ' . $content, true);
    }

    /**
     * Notification de maintenance
     */
    public static function maintenance($message)
    {
        return self::sendToAll('Maintenance', $message, true);
    }

    /**
     * Compter les notifications non lues d'un utilisateur
     */
    public static function getUnreadCount($userId = null)
    {
        $query = Notification::where('statut', 'unread');

        if ($userId) {
            $query->where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereNull('user_id');
            });
        }

        return $query->count();
    }
}