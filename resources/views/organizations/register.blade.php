@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-building me-2"></i> Créer une société</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Complète les informations ci‑dessous pour créer ta société et le compte administrateur. Le mot de passe restera secret et l'admin pourra gérer l'organisation immédiatement après création.</p>

                    <form method="POST" action="{{ route('organizations.register.store') }}" id="org-register-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nom de l'organisation</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Ex : Agence Sud" required autofocus>
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Identifiant (slug)</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" class="form-control" placeholder="ex: agence-sud">
                            <div class="form-text">Généré automatiquement à partir du nom; modifie-le si nécessaire (minuscules, tirets).</div>
                            @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email de contact</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="form-control" placeholder="contact@exemple.com">
                            @error('contact_email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <hr>
                        <h5>Compte administrateur</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom complet</label>
                                <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="form-control" placeholder="Prénom Nom" required>
                                @error('admin_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adresse email</label>
                                <input type="email" name="admin_email" value="{{ old('admin_email') }}" class="form-control" placeholder="admin@exemple.com" required>
                                @error('admin_email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="admin_password" id="admin_password" class="form-control" placeholder="Min. 8 caractères" required>
                                <div class="mt-1">
                                    <small id="pw-strength" class="text-muted">Force du mot de passe : <span id="pw-strength-text">—</span></small>
                                </div>
                                @error('admin_password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="admin_password_confirmation" class="form-control" placeholder="Confirmer le mot de passe" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">En cliquant sur Créer, tu acceptes nos conditions d'utilisation.</small>
                            <button class="btn btn-primary btn-lg" id="create-org-btn"><i class="fas fa-check me-2"></i>Créer la société</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple slugify function: lowercase, remove diacritics, replace non-alnum with dashes
    function slugify(text) {
        return text.toString().normalize('NFD')
            .replace(/\p{Diacritic}/gu, '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.querySelector('input[name="slug"]');

        if (!nameInput || !slugInput) return;

        let manual = false;

        slugInput.addEventListener('input', function () { manual = true; });

        nameInput.addEventListener('input', function (e) {
            if (manual) return; // don't overwrite if user edited slug
            const s = slugify(e.target.value || '');
            slugInput.value = s;
        });

        // password strength
        const pwInput = document.getElementById('admin_password');
        const pwText = document.getElementById('pw-strength-text');
        function scorePassword(pw) {
            if (!pw) return 0;
            let score = 0;
            if (pw.length >= 8) score += 1;
            if (/[A-Z]/.test(pw)) score += 1;
            if (/[0-9]/.test(pw)) score += 1;
            if (/[^A-Za-z0-9]/.test(pw)) score += 1;
            return score;
        }
        function updatePwText() {
            const s = scorePassword(pwInput.value);
            const labels = ['Très faible', 'Faible', 'Moyen', 'Bon', 'Excellent'];
            pwText.textContent = labels[s] || '—';
            pwText.className = s < 2 ? 'text-danger' : (s < 4 ? 'text-warning' : 'text-success');
        }
        if (pwInput) {
            pwInput.addEventListener('input', updatePwText);
        }

        // simple client-side guarding to avoid blank name/slug
        const form = document.getElementById('org-register-form');
        form.addEventListener('submit', function (e) {
            const name = nameInput.value.trim();
            if (!name) {
                e.preventDefault();
                alert('Le nom de l\'organisation est requis.');
                nameInput.focus();
                return false;
            }
            const email = document.querySelector('input[name="admin_email"]').value.trim();
            if (!email) {
                e.preventDefault();
                alert('L\'adresse email de l\'admin est requise.');
                return false;
            }
        });
    });
</script>
@endpush
