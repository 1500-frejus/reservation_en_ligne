@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Modifier la Notification
                    </h3>
                </div>

                <form action="{{ route('admin.notifications.update', $notification) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Utilisateur</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Système (tous les utilisateurs)</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $notification->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statut">Statut *</label>
                                    <select name="statut" id="statut" class="form-control" required>
                                        <option value="unread" {{ $notification->statut == 'unread' ? 'selected' : '' }}>Non lue</option>
                                        <option value="read" {{ $notification->statut == 'read' ? 'selected' : '' }}>Lue</option>
                                        <option value="archived" {{ $notification->statut == 'archived' ? 'selected' : '' }}>Archivée</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="type">Type de notification *</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="Information" {{ $notification->type == 'Information' ? 'selected' : '' }}>Information</option>
                                <option value="Avertissement" {{ $notification->type == 'Avertissement' ? 'selected' : '' }}>Avertissement</option>
                                <option value="Promotion" {{ $notification->type == 'Promotion' ? 'selected' : '' }}>Promotion</option>
                                <option value="Maintenance" {{ $notification->type == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Annonce" {{ $notification->type == 'Annonce' ? 'selected' : '' }}>Annonce</option>
                                <option value="Rappel" {{ $notification->type == 'Rappel' ? 'selected' : '' }}>Rappel</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contenu">Contenu de la notification *</label>
                            <textarea name="contenu" id="contenu" class="form-control" rows="5" required>{{ $notification->contenu }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection