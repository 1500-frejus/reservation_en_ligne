@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-alt"></i> Détails de l'Horaire #{{ $schedule->id }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations générales</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>ID:</th>
                                <td>{{ $schedule->id }}</td>
                            </tr>
                            <tr>
                                <th>Trajet:</th>
                                <td>{{ $schedule->route->depart }} → {{ $schedule->route->arrivee }}</td>
                            </tr>
                            <tr>
                                <th>Départ:</th>
                                <td>{{ $schedule->departure_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Prix:</th>
                                <td>{{ number_format($schedule->price, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Places disponibles:</th>
                                <td>{{ $schedule->seats_available ?? 'Illimité' }}</td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $schedule->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Statistiques</h5>
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h2 class="text-primary">{{ $schedule->bookings->count() }}</h2>
                                <p class="mb-0">Réservations</p>
                            </div>
                        </div>
                        @if($schedule->seats_available)
                            <div class="mt-3">
                                <small class="text-muted">Places restantes: {{ $schedule->seats_available - $schedule->bookings->sum('seats') }}</small>
                                <div class="progress mt-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $schedule->bookings->sum('seats') / $schedule->seats_available * 100 }}%"
                                         aria-valuenow="{{ $schedule->bookings->sum('seats') }}"
                                         aria-valuemin="0"
                                         aria-valuemax="{{ $schedule->seats_available }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-warning btn-sm mb-2 w-100">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary btn-sm w-100">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>

        @if($schedule->bookings->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Dernières réservations</h5>
                </div>
                <div class="card-body">
                    @foreach($schedule->bookings->take(5) as $booking)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted">{{ $booking->passenger_name }}</small><br>
                                <small>{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <span class="badge bg-info">{{ $booking->seats }} place(s)</span>
                        </div>
                    @endforeach
                    @if($schedule->bookings->count() > 5)
                        <small class="text-muted">... et {{ $schedule->bookings->count() - 5 }} autres</small>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection