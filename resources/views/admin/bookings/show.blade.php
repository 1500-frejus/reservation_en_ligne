@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-ticket-alt"></i> Détails de la Réservation #{{ $booking->id }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations du passager</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom:</strong></td>
                                <td>{{ $booking->passenger_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $booking->passenger_email }}</td>
                            </tr>
                        </table>

                        <h5>Détails du trajet</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Trajet:</strong></td>
                                <td>{{ $booking->route->depart }} → {{ $booking->route->arrivee }}</td>
                            </tr>
                            <tr>
                                <td><strong>Horaire:</strong></td>
                                <td>{{ $booking->route->horaire->format('H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Durée:</strong></td>
                                <td>{{ $booking->route->duree_minutes }} minutes</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informations de réservation</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>#{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut:</strong></td>
                                <td>
                                    @if($booking->status == 'confirmed')
                                        <span class="badge bg-success">Confirmée</span>
                                    @elseif($booking->status == 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Prix:</strong></td>
                                <td><strong>{{ $booking->payment ? $booking->payment->montant . '€' : 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Mode de paiement:</strong></td>
                                <td>{{ $booking->payment ? ucfirst($booking->payment->mode) : 'N/A' }}</td>
                            </tr>
                        </table>

                        @if($booking->ticket)
                        <h5>Ticket</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>QR Code:</strong></td>
                                <td>
                                    @php
                                        $qrData = $booking->ticket->qr_code;
                                    @endphp
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($booking->ticket->qr_code) }}" alt="QR Code" style="width: 100px; height: 100px;">
                                </td>
                            </tr>
                        </table>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h5>Dates</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Créée le:</strong></td>
                                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Modifiée le:</strong></td>
                                <td>{{ $booking->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier le statut
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection