@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bell"></i> Gestion des Notifications
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nouvelle Notification
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Type</th>
                                    <th>Contenu</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->id }}</td>
                                    <td>
                                        @if($notification->user)
                                            {{ $notification->user->name }}<br>
                                            <small class="text-muted">{{ $notification->user->email }}</small>
                                        @else
                                            <span class="text-muted">Système</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $notification->type }}</span>
                                    </td>
                                    <td>
                                        {{ Str::limit($notification->contenu, 50) }}
                                    </td>
                                    <td>
                                        @if($notification->statut == 'unread')
                                            <span class="badge badge-warning">Non lue</span>
                                        @elseif($notification->statut == 'read')
                                            <span class="badge badge-success">Lue</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $notification->statut }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($notification->statut == 'unread')
                                            <form action="{{ route('admin.notifications.mark-read', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success btn-sm" title="Marquer comme lue">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Aucune notification trouvée.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection