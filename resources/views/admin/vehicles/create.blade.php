@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-plus"></i> Créer un Nouveau Véhicule</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vehicles.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="immatriculation" class="form-label">Immatriculation</label>
                            <input type="text" class="form-control" id="immatriculation" name="immatriculation" value="{{ old('immatriculation') }}" required>
                            @error('immatriculation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type_id" class="form-label">Type de véhicule</label>
                            <select class="form-select" id="type_id" name="type_id" required>
                                <option value="">Choisir un type</option>
                                @foreach($vehicleTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacite" class="form-label">Capacité</label>
                            <input type="number" class="form-control" id="capacite" name="capacite" value="{{ old('capacite') }}" min="1" required>
                            @error('capacite')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut" required>
                                <option value="active" {{ old('statut') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ old('statut') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                <option value="maintenance" {{ old('statut') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('statut')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Créer le Véhicule
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection