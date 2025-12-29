@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-plus"></i> Créer un Nouvel Horaire</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.schedules.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="route_id" class="form-label">Trajet</label>
                            <select class="form-select" id="route_id" name="route_id" required>
                                <option value="">Sélectionner un trajet</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                                        {{ $route->depart }} → {{ $route->arrivee }} ({{ $route->prix }}€)
                                    </option>
                                @endforeach
                            </select>
                            @error('route_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departure_at" class="form-label">Date et heure de départ</label>
                            <input type="datetime-local" class="form-control" id="departure_at" name="departure_at"
                                   value="{{ old('departure_at') }}" required>
                            @error('departure_at')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Prix (€)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                   value="{{ old('price') }}" required>
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="seats_available" class="form-label">Places disponibles</label>
                            <input type="number" class="form-control" id="seats_available" name="seats_available"
                                   value="{{ old('seats_available', 40) }}" required>
                            <small class="form-text text-muted">Laissez vide pour places illimitées</small>
                            @error('seats_available')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer l'horaire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Définir la date minimale à maintenant + 1 heure
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setHours(now.getHours() + 1); // +1 heure minimum
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('departure_at').min = minDateTime;
});
</script>
@endsection