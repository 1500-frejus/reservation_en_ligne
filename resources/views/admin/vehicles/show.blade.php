@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bus"></i> Détails du Véhicule</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations générales</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Immatriculation:</strong></td>
                                <td>{{ $vehicle->immatriculation }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>{{ $vehicle->type->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Capacité:</strong></td>
                                <td>{{ $vehicle->capacite }} passagers</td>
                            </tr>
                            <tr>
                                <td><strong>Statut:</strong></td>
                                <td>
                                    @if($vehicle->statut == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @elseif($vehicle->statut == 'inactive')
                                        <span class="badge bg-secondary">Inactif</span>
                                    @else
                                        <span class="badge bg-warning">Maintenance</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Dates</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Créé le:</strong></td>
                                <td>{{ $vehicle->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Modifié le:</strong></td>
                                <td>{{ $vehicle->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection