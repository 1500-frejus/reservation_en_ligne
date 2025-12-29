@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <div class="bg-white rounded-pill shadow-lg p-4 mx-auto" style="max-width: 600px;">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h1 class="h3 text-dark font-weight-bold mb-2">Réservation Confirmée</h1>
                        <p class="text-muted mb-0">Votre billet a été réservé avec succès</p>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-ticket-alt me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h4 class="mb-1">Billet #{{ $booking->id }}</h4>
                                <small>Réservé le {{ $booking->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Passenger & Trip Details -->
                            <div class="col-lg-8 p-4">
                                <!-- Passenger Info -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Informations du Passager
                                    </h5>
                                    <div class="bg-light rounded-3 p-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <strong class="text-muted">Nom:</strong><br>
                                                <span class="h6">{{ $booking->passenger_name }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong class="text-muted">Email:</strong><br>
                                                <span class="h6">{{ $booking->passenger_email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trip Details -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-route me-2"></i>Détails du Trajet
                                    </h5>
                                    <div class="bg-light rounded-3 p-3">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-muted">Départ:</strong><br>
                                                        <span class="h6">{{ $booking->route->depart }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-muted">Arrivée:</strong><br>
                                                        <span class="h6">{{ $booking->route->arrivee }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong class="text-muted">Horaire:</strong><br>
                                                <span class="h6">{{ $booking->route->horaire->format('H:i') }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong class="text-muted">Durée:</strong><br>
                                                <span class="h6">{{ $booking->route->duree_minutes }} min</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Status & Price -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Statut & Paiement
                                    </h5>
                                    <div class="bg-light rounded-3 p-3">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <strong class="text-muted">Statut:</strong><br>
                                                <span class="badge bg-success fs-6 px-3 py-2">{{ $booking->status }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong class="text-muted">Prix Total:</strong><br>
                                                <span class="h5 text-success">{{ number_format($booking->route->prix * $booking->seats, 2) }}€</span>
                                                <small class="text-muted d-block">({{ $booking->seats }} place{{ $booking->seats > 1 ? 's' : '' }})</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Section -->
                            <div class="col-lg-4 bg-gradient-secondary text-white p-4 d-flex flex-column align-items-center justify-content-center">
                                <h5 class="mb-4 text-center">
                                    <i class="fas fa-qrcode me-2"></i>QR Code du Billet
                                </h5>
                                <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($booking->ticket->qr_code) }}" 
                                         alt="QR Code" 
                                         class="img-fluid rounded">
                                </div>
                                <p class="text-center small mb-4">
                                    <i class="fas fa-mobile-alt me-1"></i>
                                    Présentez ce QR code à l'embarquement
                                </p>
                                <div class="text-center">
                                    <button onclick="window.print()" class="btn btn-light btn-lg shadow-sm">
                                        <i class="fas fa-print me-2"></i>Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="card-footer bg-light p-4">
                        <div class="alert alert-info border-0 rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-envelope text-info me-3 mt-1"></i>
                                <div>
                                    <strong>Confirmation envoyée</strong><br>
                                    Un email de confirmation vous a été envoyé à {{ $booking->passenger_email }}.
                                    Pour toute modification, contactez notre service client.
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg me-3">
                                <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                            <a href="{{ route('bookings.download-pdf', $booking) }}" class="btn btn-success btn-lg me-3">
                                <i class="fas fa-download me-2"></i>Télécharger PDF
                            </a>
                            <a href="{{ route('bookings.history') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-history me-2"></i>Mes Réservations
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.bg-gradient-secondary {
    background: linear-gradient(45deg, #6c757d, #495057);
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.rounded-3 {
    border-radius: 1rem !important;
}

@media print {
    body * {
        visibility: hidden;
    }
    .card, .card * {
        visibility: visible;
    }
    .card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
    .btn, .alert, .card-footer {
        display: none !important;
    }
    .bg-gradient-primary, .bg-gradient-secondary {
        background: white !important;
        color: black !important;
    }
}
</style>

<script>
// Add some animation on load
document.addEventListener('DOMContentLoaded', function() {
    const card = document.querySelector('.card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    setTimeout(() => {
        card.style.transition = 'all 0.5s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 100);
});
</script>
@endsection
