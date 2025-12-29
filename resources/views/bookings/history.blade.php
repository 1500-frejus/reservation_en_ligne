@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4"><i class="fas fa-history"></i> Historique des Réservations</h1>

        @if($bookings->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trajet</th>
                                    <th>Date</th>
                                    <th>Passager</th>
                                    <th>Prix</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->route->depart }} → {{ $booking->route->arrivee }}</td>
                                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $booking->passenger_name }}</td>
                                    <td>{{ $booking->payment ? $booking->payment->montant . '€' : 'N/A' }}</td>
                                    <td>
                                        @if($booking->status == 'confirmed')
                                            <span class="badge bg-success">Confirmée</span>
                                        @elseif($booking->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger">Annulée</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <h4>Aucune réservation trouvée</h4>
                    <p>Vous n'avez pas encore effectué de réservation.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher des trajets
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection