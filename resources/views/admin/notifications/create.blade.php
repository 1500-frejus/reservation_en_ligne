@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Créer une Notification
                    </h3>
                </div>

                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Utilisateur (optionnel)</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Laissez vide pour envoyer à tous les utilisateurs
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type de notification *</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="Information">Information</option>
                                        <option value="Avertissement">Avertissement</option>
                                        <option value="Promotion">Promotion</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Annonce">Annonce</option>
                                        <option value="Rappel">Rappel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contenu">Contenu de la notification *</label>
                            <textarea name="contenu" id="contenu" class="form-control" rows="5" required
                                      placeholder="Saisissez le contenu de votre notification..."></textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="1">
                                <label class="custom-control-label" for="send_email">
                                    Envoyer également par email
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Envoyer la Notification
                        </button>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-flask"></i> Test d'Email
                    </h4>
                </div>

                <div class="card-body">
                    <p class="text-muted">
                        Testez l'envoi d'email avant d'envoyer une notification massive.
                    </p>

                    <form action="{{ route('admin.notifications.send-test') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="test_email">Email de test</label>
                            <input type="email" name="test_email" id="test_email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="test_type">Type</label>
                            <input type="text" name="test_type" id="test_type" class="form-control" value="Test" required>
                        </div>

                        <div class="form-group">
                            <label for="test_contenu">Contenu</label>
                            <textarea name="test_contenu" id="test_contenu" class="form-control" rows="3" required>Ceci est un email de test pour vérifier le système de notifications.</textarea>
                        </div>

                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-envelope"></i> Envoyer Email Test
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection