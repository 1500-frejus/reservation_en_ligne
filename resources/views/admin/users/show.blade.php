@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user"></i> Détails de l'Utilisateur</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations personnelles</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nom:</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Téléphone:</strong></td>
                                <td>{{ $user->telephone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rôle:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $user->role->name == 'admin' ? 'danger' : ($user->role->name == 'user' ? 'primary' : 'info') }}">
                                        {{ ucfirst($user->role->name) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Dates et statistiques</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Inscription:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dernière connexion:</strong></td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Réservations:</strong></td>
                                <td>{{ $user->bookings->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($user->bookings->count() > 0)
                <hr>
                <h5>Réservations récentes</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Trajet</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->bookings->take(5) as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->route->depart }} → {{ $booking->route->arrivee }}</td>
                                <td>{{ $booking->date->format('d/m/Y') }}</td>
                                <td>
                                    @if($booking->statut == 'confirmed')
                                        <span class="badge bg-success">Confirmée</span>
                                    @elseif($booking->statut == 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>{{ $booking->payment ? $booking->payment->montant . '€' : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection