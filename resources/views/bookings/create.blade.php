@extends('layouts.app')

@section('content')
<div class="booking-page" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 1rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Compact Progress Steps -->
                <div class="text-center mb-4">
                    <div class="progress-steps d-flex justify-content-center align-items-center">
                        <div class="step completed">
                            <div class="step-circle">
                                <i class="fas fa-search"></i>
                            </div>
                            <small class="step-label">Recherche</small>
                        </div>
                        <div class="step-connector completed"></div>
                        <div class="step active">
                            <div class="step-circle">
                                <i class="fas fa-user"></i>
                            </div>
                            <small class="step-label">Réservation</small>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step">
                            <div class="step-circle">
                                <i class="fas fa-check"></i>
                            </div>
                            <small class="step-label">Confirmation</small>
                        </div>
                    </div>
                </div>

                <!-- Main Booking Card -->
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ticket-alt me-2"></i>
                                <h5 class="mb-0">Finaliser Votre Réservation</h5>
                            </div>
                            <div class="text-end">
                                <div class="h4 mb-0">{{ number_format($route->prix * $passengers, 2) }}€</div>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Compact Trip Summary -->
                        <div class="trip-summary bg-light rounded-3 p-3 mb-4">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="route-point bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-map-marker-alt fa-sm"></i>
                                        </div>
                                        <div class="me-3">
                                            <div class="fw-bold">{{ $route->depart }}</div>
                                            <small class="text-muted">Départ</small>
                                        </div>
                                        <i class="fas fa-arrow-right text-primary mx-2"></i>
                                        <div class="route-point bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-flag-checkered fa-sm"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $route->arrivee }}</div>
                                            <small class="text-muted">Arrivée</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row text-center g-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Passagers</small>
                                            <strong>{{ $passengers }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Prix total</small>
                                            <strong>{{ number_format($route->prix * $passengers, 2) }}€</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Selection -->
                        @if($schedules->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Sélectionner un Horaire
                                </h6>
                                <div class="schedule-options">
                                    @foreach($schedules as $schedule)
                                        <div class="schedule-option card mb-2 {{ $loop->first ? 'border-primary' : '' }}" onclick="selectSchedule({{ $schedule->id }}, {{ $schedule->price }})">
                                            <div class="card-body p-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-clock text-primary me-3"></i>
                                                            <div>
                                                                <strong>{{ $schedule->departure_at->format('d/m/Y H:i') }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    @if($schedule->seats_available)
                                                                        {{ $schedule->seats_available - $schedule->bookings->sum('seats') }} places restantes
                                                                    @else
                                                                        Places illimitées
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <strong class="text-primary">{{ number_format($schedule->price * $passengers, 2) }}€</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedules->first()->id ?? '' }}" required>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Aucun horaire disponible pour ce trajet. Veuillez contacter l'administrateur.
                            </div>
                        @endif

                        <!-- Booking Form -->
                        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                            @csrf
                            <input type="hidden" name="route_id" value="{{ $route->id }}">
                            <input type="hidden" name="passengers" value="{{ $passengers }}">

                            <!-- Passenger Information -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-circle me-2"></i>Informations du Passager
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom *" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom *" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email *" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Téléphone *" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-credit-card me-2"></i>Mode de Paiement
                                </h6>
                                <div class="payment-options">
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="payment-option card h-100 text-center p-2 border-primary" onclick="selectPayment('carte')">
                                                <i class="fas fa-credit-card text-primary mb-1"></i>
                                                <small class="fw-bold">Carte</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="payment-option card h-100 text-center p-2" onclick="selectPayment('paypal')">
                                                <i class="fab fa-paypal text-primary mb-1"></i>
                                                <small class="fw-bold">PayPal</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="payment-option card h-100 text-center p-2" onclick="selectPayment('especes')">
                                                <i class="fas fa-money-bill-wave text-primary mb-1"></i>
                                                <small class="fw-bold">Espèces</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="payment_mode" id="payment_mode" value="carte" required>
                                </div>
                            </div>

                            <!-- Terms and Submit -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        J'accepte les <a href="#" class="text-primary">CGV</a> et la <a href="#" class="text-primary">politique de confidentialité</a>
                                    </label>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg px-4 py-2">
                                    <i class="fas fa-lock me-2"></i>Confirmer ({{ number_format($route->prix * $passengers, 2) }}€)
                                </button>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-shield-alt me-1"></i>Paiement sécurisé
                                </p>
                            </div>
                        </form>
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

.shadow-lg {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.rounded-3 {
    border-radius: 0.75rem !important;
}

.progress-steps {
    margin-bottom: 1.5rem;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    transition: all 0.3s ease;
}

.step.completed .step-circle {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
}

.step.active .step-circle {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
}

.step-connector {
    width: 50px;
    height: 2px;
    background: #e9ecef;
    margin: 0 0.5rem;
    align-self: center;
}

.step.completed + .step-connector {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.step-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6c757d;
}

.step.completed .step-label,
.step.active .step-label {
    color: #495057;
}

.route-point {
    flex-shrink: 0;
}

.trip-summary {
    border: 1px solid #dee2e6;
}

.payment-option {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
}

.payment-option.selected {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.schedule-option {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
}

.schedule-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.schedule-option.selected {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}


.payment-option.selected {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.form-control {
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}
</style>

<script>
function selectSchedule(scheduleId, price) {
    // Retirer la classe selected de tous les horaires
    document.querySelectorAll('.schedule-option').forEach(option => {
        option.classList.remove('selected');
        option.classList.remove('border-primary');
    });

    // Ajouter la classe selected à l'horaire sélectionné
    event.currentTarget.classList.add('selected');
    event.currentTarget.classList.add('border-primary');

    // Mettre à jour le champ caché
    document.getElementById('schedule_id').value = scheduleId;

    // Calculer et afficher le nouveau prix total
    const passengers = {{ $passengers }};
    const totalPrice = price * passengers;
    const priceElements = document.querySelectorAll('.schedule-option.selected .text-primary');
    if (priceElements.length > 0) {
        // Mettre à jour l'affichage du prix dans l'option sélectionnée
        priceElements[priceElements.length - 1].textContent = totalPrice.toFixed(2) + '€';
    }
}

function selectPayment(mode) {
    document.querySelectorAll('.payment-option').forEach(option => {
        option.classList.remove('selected');
        option.classList.remove('border-primary');
    });
    event.currentTarget.classList.add('selected');
    event.currentTarget.classList.add('border-primary');
    document.getElementById('payment_mode').value = mode;
}

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner le premier horaire par défaut
    const firstSchedule = document.querySelector('.schedule-option');
    if (firstSchedule) {
        firstSchedule.click();
    }

    // Sélectionner le premier mode de paiement par défaut
    const firstPayment = document.querySelector('.payment-option');
    if (firstPayment) {
        firstPayment.click();
    }
});
</script>
@endsection
