@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-route"></i> Détails du Trajet</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>{{ $route->depart }}</h4>
                        <p class="text-muted">Départ</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h4>{{ $route->arrivee }}</h4>
                        <p class="text-muted">Arrivée</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <i class="fas fa-euro-sign fa-2x text-success"></i>
                        <h5>À partir de {{ $route->prix }}€</h5>
                        <p>Prix de base</p>
                    </div>
                    <div class="col-md-6">
                        <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        <h5>{{ $route->schedules->count() }} horaires</h5>
                        <p>Disponibles</p>
                    </div>
                </div>
            </div>
        </div>

        @if($route->schedules->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="fas fa-clock"></i> Horaires disponibles</h5>
                    <small class="text-muted">Cliquez sur un horaire pour le sélectionner</small>
                </div>
                <div class="card-body">
                    <div class="schedule-options">
                        @foreach($route->schedules()->where('departure_at', '>', now())->orderBy('departure_at')->get() as $schedule)
                            <div class="schedule-option card mb-3" onclick="selectSchedule({{ $schedule->id }}, '{{ $schedule->departure_at->format('d/m/Y H:i') }}', {{ $schedule->price }}, {{ $schedule->seats_available - $schedule->bookings->sum('seats') }})">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-primary me-3 fa-lg"></i>
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
                                        <div class="col-md-6 text-end">
                                            <div class="h5 text-primary mb-0">{{ number_format($schedule->price, 2) }}€</div>
                                            <small class="text-muted">par personne</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-4 border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Aucun horaire disponible</h5>
                    <p class="text-muted">Il n'y a actuellement aucun horaire disponible pour ce trajet.</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-primary text-white text-center py-4">
                <h5 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Réserver ce trajet
                </h5>
            </div>
            <div class="card-body p-4">
                @auth
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="schedule_id" id="selected_schedule_id" value="">

                        <!-- Message d'aide -->
                        <div class="alert alert-info border-0 mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Étape 1:</strong> Sélectionnez un horaire ci-dessus
                            <strong>Étape 2:</strong> Remplissez vos informations
                        </div>

                        <!-- Nombre de passagers -->
                        <div class="mb-4">
                            <label for="passengers" class="form-label fw-semibold">
                                <i class="fas fa-users me-2 text-primary"></i>Nombre de passagers
                            </label>
                            <select class="form-select form-select-lg" id="passengers" name="passengers" required>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Informations personnelles
                            </h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="text" class="form-control" id="nom" name="nom"
                                           placeholder="Nom" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="prenom" name="prenom"
                                           placeholder="Prénom" required>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-primary mb-3">
                                <i class="fas fa-envelope me-2"></i>Contact
                            </h6>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="votre.email@example.com" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" id="telephone" name="telephone"
                                       placeholder="+33 6 XX XX XX XX" required>
                            </div>
                        </div>

                        <!-- Mode de paiement -->
                        <div class="mb-4">
                            <h6 class="fw-semibold text-primary mb-3">
                                <i class="fas fa-credit-card me-2"></i>Mode de paiement
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

                        <!-- Résumé et prix -->
                        <div id="booking-summary" class="mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Résumé de la réservation</h6>
                                    <div id="selected-schedule-info"></div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong id="total-price" class="text-primary">0.00€</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label small" for="terms">
                                J'accepte les <a href="#" class="text-decoration-none">conditions générales</a> et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 mt-3" id="submit-btn" disabled onclick="return validateForm()">
                            <i class="fas fa-check me-2"></i>Réserver maintenant
                        </button>
                    </form>
                @else
                    <div class="text-center">
                        <i class="fas fa-sign-in-alt fa-3x text-muted mb-3"></i>
                        <h5>Connexion requise</h5>
                        <p class="text-muted">Vous devez être connecté pour réserver un trajet.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                        <br>
                        <small class="text-muted mt-2 d-block">Pas de compte ?
                            <a href="{{ route('register') }}">S'inscrire</a>
                        </small>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
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

.payment-option {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
}

.payment-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.payment-option.selected {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>

<script>
let selectedSchedule = null;

function selectSchedule(scheduleId, dateTime, price, availableSeats) {
    selectedSchedule = { id: scheduleId, dateTime: dateTime, price: price, availableSeats: availableSeats };

    // Retirer la classe selected de tous les horaires
    document.querySelectorAll('.schedule-option').forEach(option => {
        option.classList.remove('selected');
    });

    // Ajouter la classe selected à l'horaire cliqué
    event.currentTarget.classList.add('selected');

    // Mettre à jour le formulaire
    document.getElementById('selected_schedule_id').value = scheduleId;
    document.getElementById('submit-btn').disabled = false;

    // Afficher le résumé
    updateBookingSummary();
}

function selectPayment(mode) {
    // Retirer la classe selected de toutes les options
    document.querySelectorAll('.payment-option').forEach(option => {
        option.classList.remove('selected');
    });

    // Ajouter la classe selected à l'option cliquée
    event.currentTarget.classList.add('selected');
    document.getElementById('payment_mode').value = mode;
}

function validateForm() {
    if (!selectedSchedule) {
        alert('Veuillez sélectionner un horaire avant de réserver.');
        // Scroll vers les horaires
        document.querySelector('.schedule-options').scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    const terms = document.getElementById('terms');
    if (!terms.checked) {
        alert('Veuillez accepter les conditions générales.');
        terms.focus();
        return false;
    }

    return true;
}

function updateBookingSummary() {
    if (!selectedSchedule) return;

    const passengers = parseInt(document.getElementById('passengers').value);
    const totalPrice = selectedSchedule.price * passengers;

    document.getElementById('selected-schedule-info').innerHTML = `
        <div class="mb-2">
            <i class="fas fa-clock me-2"></i>
            <strong>${selectedSchedule.dateTime}</strong>
        </div>
        <div class="mb-2">
            <i class="fas fa-users me-2"></i>
            ${passengers} passager(s)
        </div>
        <div class="mb-2">
            <i class="fas fa-chair me-2"></i>
            ${selectedSchedule.availableSeats} places restantes
        </div>
    `;

    document.getElementById('total-price').textContent = totalPrice.toFixed(2) + '€';
    document.getElementById('booking-summary').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner le premier mode de paiement par défaut
    document.querySelector('.payment-option').click();

    // Mettre à jour le résumé quand le nombre de passagers change
    document.getElementById('passengers').addEventListener('change', updateBookingSummary);
});
</script>
@endsection