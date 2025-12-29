@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-route"></i> Gestion des Trajets</h1>
            <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Trajet
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
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
                                <th>Horaire</th>
                                <th>Durée</th>
                                <th>Prix</th>
                                <th>Réservations</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($routes as $route)
                            <tr>
                                <td>{{ $route->id }}</td>
                                <td>{{ $route->depart }} → {{ $route->arrivee }}</td>
                                <td>{{ $route->horaire->format('H:i') }}</td>
                                <td>{{ $route->duree_minutes }} min</td>
                                <td>{{ $route->prix }}€</td>
                                <td>{{ $route->bookings_count ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('admin.routes.show', $route) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.routes.destroy', $route) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $routes->links() }}
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
    </div>
</div>
@endsection