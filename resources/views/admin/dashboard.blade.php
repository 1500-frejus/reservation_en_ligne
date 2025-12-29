@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord Administrateur</h1>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-route"></i> Trajets</h5>
                        <h2>{{ $stats['routes'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-ticket-alt"></i> Réservations</h5>
                        <h2>{{ $stats['bookings'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Utilisateurs</h5>
                        <h2>{{ $stats['users'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-euro-sign"></i> Revenus</h5>
                        <h2>{{ $stats['revenue'] ?? 0 }}€</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Gestion</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('admin.routes.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-route"></i> Gérer les trajets
                            </a>
                            <a href="{{ route('admin.schedules.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-calendar-alt"></i> Gérer les horaires
                            </a>
                            <a href="{{ route('admin.vehicles.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-bus"></i> Gérer les véhicules
                            </a>
                            @php $user = auth()->user(); @endphp
                            @if($user && method_exists($user, 'hasRole') && ! $user->hasRole('super_admin') && $user->organization_id)
                                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-users"></i> Gérer mes clients
                                </a>
                            @else
                                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-users"></i> Gérer les utilisateurs
                                </a>
                            @endif
                            @if(auth()->check() && (method_exists(auth()->user(), 'hasRole') && (auth()->user()->hasRole('organization_admin') || auth()->user()->hasRole('super_admin'))))
                                <a href="{{ route('admin.agents.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-user-friends"></i> Gérer les agents
                                </a>
                            @endif
                            <a href="{{ route('admin.bookings.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-ticket-alt"></i> Voir les réservations
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Rapports</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('admin.reports.daily-bookings') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-calendar"></i> Réservations par jour
                            </a>
                            <a href="{{ route('admin.reports.popular-routes') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-route"></i> Trajets populaires
                            </a>
                            <a href="{{ route('admin.reports.monthly-revenue') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-euro-sign"></i> Revenus mensuels
                            </a>
                            <a href="{{ route('admin.reports.cron-logs') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-clock"></i> Historique des tâches cron
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Réservations récentes -->
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-clock"></i> Réservations Récentes</h5>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Passager</th>
                                    <th>Trajet</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->passenger_name }}</td>
                                    <td>{{ $booking->route->depart }} → {{ $booking->route->arrivee }}</td>
                                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->payment ? $booking->payment->montant . '€' : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Aucune réservation récente.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection