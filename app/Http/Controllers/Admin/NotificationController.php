<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string|max:255',
            'contenu' => 'required|string',
            'send_email' => 'boolean'
        ]);

        $data['statut'] = 'unread';

        // Si pas de user_id spécifié, c'est une notification générale
        if (!$request->user_id) {
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $data['type'],
                    'contenu' => $data['contenu'],
                    'statut' => 'unread'
                ]);

                // Envoyer email si demandé
                if ($request->send_email) {
                    try {
                        Mail::to($user->email)->send(new NotificationMail($data['type'], $data['contenu']));
                    } catch (\Exception $e) {
                        // Log l'erreur mais continue
                        \Log::error('Erreur envoi email notification: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notifications envoyées à tous les utilisateurs.');
        }

        $notification = Notification::create($data);

        // Envoyer email si demandé
        if ($request->send_email && $notification->user) {
            try {
                Mail::to($notification->user->email)->send(new NotificationMail($data['type'], $data['contenu']));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email notification: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification créée avec succès.');
    }

    public function show(Notification $notification)
    {
        $notification->load('user');
        return view('admin.notifications.show', compact('notification'));
    }

    public function edit(Notification $notification)
    {
        $users = User::all();
        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string|max:255',
            'contenu' => 'required|string',
            'statut' => 'required|in:read,unread,archived'
        ]);

        $notification->update($data);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification mise à jour avec succès.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification supprimée avec succès.');
    }

    // Méthode pour marquer comme lue
    public function markAsRead(Notification $notification)
    {
        $notification->update(['statut' => 'read']);

        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    // Méthode pour envoyer une notification de test
    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|string',
            'contenu' => 'required|string'
        ]);

        try {
            Mail::to($request->email)->send(new NotificationMail($request->type, $request->contenu));
            return redirect()->back()->with('success', 'Email de test envoyé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }
}
