@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-edit"></i> Modifier la Réservation</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5>Détails de la réservation</h5>
                    <p><strong>Passager:</strong> {{ $booking->passenger_name }}</p>
                    <p><strong>Trajet:</strong> {{ $booking->route->depart }} → {{ $booking->route->arrivee }}</p>
                    <p><strong>Date:</strong> {{ $booking->created_at->format('d/m/Y') }}</p>
                    <p><strong>Prix:</strong> {{ $booking->payment ? $booking->payment->montant . '€' : 'N/A' }}</p>
                </div>

                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">Statut de la réservation</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                            <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Mettre à jour le Statut
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection