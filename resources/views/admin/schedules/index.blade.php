@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-calendar-alt"></i> Gestion des Horaires</h1>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel Horaire
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Trajet</th>
                                <th>Départ</th>
                                <th>Prix</th>
                                <th>Places disponibles</th>
                                <th>Réservations</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->route->depart }} → {{ $schedule->route->arrivee }}</td>
                                <td>{{ $schedule->departure_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($schedule->price, 2) }}€</td>
                                <td>{{ $schedule->seats_available ?? 'Illimité' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $schedule->bookings->count() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($schedules->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $schedules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection