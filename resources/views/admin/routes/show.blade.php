@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-route"></i> Détails du Trajet</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations du trajet</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $route->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Départ:</strong></td>
                                <td>{{ $route->depart }}</td>
                            </tr>
                            <tr>
                                <td><strong>Arrivée:</strong></td>
                                <td>{{ $route->arrivee }}</td>
                            </tr>
                            <tr>
                                <td><strong>Horaire:</strong></td>
                                <td>{{ $route->horaire->format('H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Durée:</strong></td>
                                <td>{{ $route->duree_minutes }} minutes</td>
                            </tr>
                            <tr>
                                <td><strong>Prix:</strong></td>
                                <td><strong>{{ $route->prix }}€</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Statistiques</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Réservations totales:</strong></td>
                                <td>{{ $route->bookings->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Revenus totaux:</strong></td>
                                <td>{{ $route->bookings->sum(function($booking) { return $booking->payment ? $booking->payment->montant : 0; }) }}€</td>
                            </tr>
                        </table>

                        <h5>Dates</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Créé le:</strong></td>
                                <td>{{ $route->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Modifié le:</strong></td>
                                <td>{{ $route->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($route->bookings->count() > 0)
                <hr>
                <h5>Dernières réservations</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Passager</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($route->bookings->take(5) as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->nom }} {{ $booking->prenom }}</td>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection