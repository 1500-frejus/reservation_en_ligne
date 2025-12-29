@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bell"></i> Mes Notifications
                    </h4>
                </div>

                <div class="card-body">
                    @auth
                        @php
                            $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->orWhereNull('user_id')
                                ->latest()
                                ->get();
                        @endphp

                        @if($notifications->count() > 0)
                            <div class="notifications-list">
                                @foreach($notifications as $notification)
                                <div class="notification-item card mb-3 {{ $notification->statut == 'unread' ? 'border-primary' : 'border-light' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge badge-info me-2">{{ $notification->type }}</span>
                                                    @if($notification->statut == 'unread')
                                                        <span class="badge badge-primary">Nouveau</span>
                                                    @endif
                                                </div>
                                                <p class="mb-2">{{ $notification->contenu }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            @if($notification->statut == 'unread')
                                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="ms-2">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Marquer comme lue">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune notification</h5>
                                <p class="text-muted">Vous n'avez pas encore reçu de notifications.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-sign-in-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Connexion requise</h5>
                            <p class="text-muted">Vous devez être connecté pour voir vos notifications.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-item.unread {
    border-left: 4px solid #007bff;
    background-color: #f8f9ff;
}
</style>
@endsection