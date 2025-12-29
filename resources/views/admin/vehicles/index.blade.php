@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-bus"></i> Gestion des Véhicules</h1>
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Véhicule
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
                                <th>Immatriculation</th>
                                <th>Type</th>
                                <th>Capacité</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->id }}</td>
                                <td>{{ $vehicle->immatriculation }}</td>
                                <td>{{ $vehicle->type->name }}</td>
                                <td>{{ $vehicle->capacite }}</td>
                                <td>
                                    @if($vehicle->statut == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @elseif($vehicle->statut == 'inactive')
                                        <span class="badge bg-secondary">Inactif</span>
                                    @else
                                        <span class="badge bg-warning">Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}" class="d-inline">
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

                {{ $vehicles->links() }}
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