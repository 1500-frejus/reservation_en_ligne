<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            backdrop-filter: blur(10px);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .alert {
            border: none;
            border-radius: 10px;
        }
        .sticky-top {
            top: 0;
            z-index: 1030;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <i class="fas fa-bus fa-lg me-2"></i>
                <span class="fs-4">VoyageExpress</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('bookings.history') }}">
                            <i class="fas fa-history me-1"></i>Mes Réservations
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-concierge-bell me-1"></i>Services
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-bus me-2"></i>Bus</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-taxi me-2"></i>Taxi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>À propos</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        @php
                            $isAdminMenu = false;
                            $user = auth()->user();
                            if ($user) {
                                if (method_exists($user, 'hasRole')) {
                                    $isAdminMenu = $user->hasRole('super_admin') || $user->hasRole('organization_admin');
                                }
                                // also show admin menu to users belonging to an organization
                                if (!$isAdminMenu && $user->organization_id) {
                                    $isAdminMenu = true;
                                }
                            }
                        @endphp
                        @if($isAdminMenu)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-shield me-1"></i>Administration
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.bookings.index') }}"><i class="fas fa-list me-2"></i>Réservations</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.routes.index') }}"><i class="fas fa-route me-2"></i>Trajets</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.notifications.index') }}"><i class="fas fa-bell me-2"></i>Notifications</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.history') }}">
                                <i class="fas fa-history me-1"></i>Mes Réservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell me-1"></i>Notifications
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                        ->where('statut', 'unread')
                                        ->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light ms-2">
                                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Inscription
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light ms-2" href="{{ route('organizations.register') }}" role="button">
                                <i class="fas fa-building me-1"></i>Créer une société
                                <span class="badge bg-danger ms-2">Nouveau</span>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
        {{-- allow views to push scripts to the bottom of the layout --}}
        @stack('scripts')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light mt-5 py-5">
        <div class="container">
            <div class="row">
                <!-- Section À propos -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-bus fa-2x text-primary me-3"></i>
                        <h5 class="mb-0 fw-bold">VoyageExpress</h5>
                    </div>
                    <p class="mb-3">Votre partenaire de confiance pour des voyages en bus confortables et sécurisés. Réservez vos billets en ligne facilement et profitez d'une expérience de voyage exceptionnelle.</p>
                    <div class="d-flex">
                        <a href="#" class="text-light me-3 fs-4" title="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-light me-3 fs-4" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-light me-3 fs-4" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-light fs-4" title="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Liens rapides -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Liens Rapides</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-light text-decoration-none">
                                <i class="fas fa-home me-2"></i>Accueil
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('routes.index') }}" class="text-light text-decoration-none">
                                <i class="fas fa-route me-2"></i>Routes
                            </a>
                        </li>
                        @auth
                        <li class="mb-2">
                            <a href="{{ route('bookings.history') }}" class="text-light text-decoration-none">
                                <i class="fas fa-history me-2"></i>Mes Réservations
                            </a>
                        </li>
                        @endauth
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                <i class="fas fa-info-circle me-2"></i>À propos
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Services</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                <i class="fas fa-ticket-alt me-2"></i>Réservation en ligne
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                <i class="fas fa-clock me-2"></i>Horaires
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                <i class="fas fa-shield-alt me-2"></i>Sécurité
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                <i class="fas fa-headset me-2"></i>Support
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Contactez-nous</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                        <span>123 Avenue des Voyages, Ville, Pays</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone me-3 text-primary"></i>
                        <span>+33 1 23 45 67 89</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope me-3 text-primary"></i>
                        <span>contact@voyageexpress.com</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-clock me-3 text-primary"></i>
                        <span>Lun-Dim: 8h00 - 20h00</span>
                    </div>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </button>
                </div>
            </div>

            <!-- Barre de séparation -->
            <hr class="my-4">

            <!-- Copyright -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 VoyageExpress. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-light text-decoration-none me-3">Politique de confidentialité</a>
                    <a href="#" class="text-light text-decoration-none me-3">Conditions d'utilisation</a>
                    <a href="#" class="text-light text-decoration-none">Mentions légales</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Rafraîchir le token CSRF périodiquement
        setInterval(function() {
            fetch('/sanctum/csrf-cookie', {
                method: 'GET',
                credentials: 'same-origin'
            });
        }, 300000); // Toutes les 5 minutes
    </script>
</body>
</html>
