@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-plus"></i> Créer un Nouveau Trajet</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.routes.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="depart" class="form-label">Ville de départ</label>
                            <input type="text" class="form-control" id="depart" name="depart" value="{{ old('depart') }}" required>
                            @error('depart')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="arrivee" class="form-label">Ville d'arrivée</label>
                            <input type="text" class="form-control" id="arrivee" name="arrivee" value="{{ old('arrivee') }}" required>
                            @error('arrivee')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="horaire" class="form-label">Horaire</label>
                            <input type="time" class="form-control" id="horaire" name="horaire" value="{{ old('horaire') }}" required>
                            @error('horaire')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duree_minutes" class="form-label">Durée (minutes)</label>
                            <input type="number" class="form-control" id="duree_minutes" name="duree_minutes" value="{{ old('duree_minutes') }}" min="1" required>
                            @error('duree_minutes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="prix" class="form-label">Prix (€)</label>
                            <input type="number" class="form-control" id="prix" name="prix" value="{{ old('prix') }}" step="0.01" min="0" required>
                            @error('prix')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Créer le Trajet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection