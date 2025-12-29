@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i> Détails de la Notification
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID:</label>
                                <p class="form-control-plaintext">{{ $notification->id }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Statut:</label>
                                <p class="form-control-plaintext">
                                    @if($notification->statut == 'unread')
                                        <span class="badge badge-warning">Non lue</span>
                                    @elseif($notification->statut == 'read')
                                        <span class="badge badge-success">Lue</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $notification->statut }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Utilisateur:</label>
                                <p class="form-control-plaintext">
                                    @if($notification->user)
                                        {{ $notification->user->name }}<br>
                                        <small class="text-muted">{{ $notification->user->email }}</small>
                                    @else
                                        <span class="text-muted">Système (tous les utilisateurs)</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge badge-info">{{ $notification->type }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Contenu:</label>
                        <div class="card">
                            <div class="card-body">
                                {!! nl2br(e($notification->contenu)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Créée le:</label>
                                <p class="form-control-plaintext">{{ $notification->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Modifiée le:</label>
                                <p class="form-control-plaintext">{{ $notification->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-cogs"></i> Actions
                    </h4>
                </div>

                <div class="card-body">
                    @if($notification->statut == 'unread')
                    <form action="{{ route('admin.notifications.mark-read', $notification) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-check"></i> Marquer comme lue
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit"></i> Modifier
                    </a>

                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection