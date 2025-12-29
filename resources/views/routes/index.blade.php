@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Réservez votre trajet</h1>
                <p class="lead mb-4">Voyagez en bus ou taxi de manière simple et sécurisée. Trouvez les meilleurs trajets pour vos déplacements.</p>
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                        <div>Sécurisé</div>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <div>Rapide</div>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-euro-sign fa-2x mb-2"></i>
                        <div>Économique</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-bus fa-8x text-white-50"></i>
            </div>
        </div>
    </div>
</div>

@guest
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle fa-lg me-3"></i>
        <div>
            <strong>Créez un compte pour réserver !</strong> Inscrivez-vous gratuitement pour accéder à toutes nos fonctionnalités de réservation.
            <a href="{{ route('register') }}" class="alert-link">S'inscrire maintenant</a>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endguest

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Rechercher un trajet</h2>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label for="depart" class="form-label fw-semibold">Ville de départ</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Ex: Paris" id="depart">
                        </div>
                        <div class="col-md-3">
                            <label for="arrivee" class="form-label fw-semibold">Ville d'arrivée</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Ex: Lyon" id="arrivee">
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label fw-semibold">Date de voyage</label>
                            <input type="date" class="form-control form-control-lg" id="date" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary btn-lg w-100" onclick="searchRoutes()">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <h3 class="mt-5 mb-4">Trajets populaires</h3>

            <div class="row" id="routes-list">
                @foreach($routes as $route)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">{{ $route->depart }} <i class="fas fa-arrow-right text-primary mx-2"></i> {{ $route->arrivee }}</h5>
                                    <small class="text-muted">Prix de base</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success fs-6">À partir de {{ $route->prix }}€</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-route text-primary me-2"></i>
                                    <span>Trajet disponible</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    <span>Horaires multiples</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <span>Trajet direct</span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('routes.show', $route) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-ticket-alt me-2"></i>Réserver maintenant
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
}

.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>

<script>
function searchRoutes() {
    const depart = document.getElementById('depart').value;
    const arrivee = document.getElementById('arrivee').value;
    const date = document.getElementById('date').value;

    // Simple client-side filtering (in production, use AJAX)
    const routes = document.querySelectorAll('#routes-list > div');
    routes.forEach(route => {
        const title = route.querySelector('.card-title').textContent.toLowerCase();
        const show = (!depart || title.includes(depart.toLowerCase())) &&
                    (!arrivee || title.includes(arrivee.toLowerCase()));
        route.style.display = show ? 'block' : 'none';
    });
}
</script>
@endsection