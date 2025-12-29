@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-clock"></i> Historique des Tâches Cron</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Logs des tâches automatiques exécutées</h5>
            </div>
            <div class="card-body">
                @if($cronLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tâche</th>
                                    <th>Statut</th>
                                    <th>Date d'exécution</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cronLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->tache }}</td>
                                    <td>
                                        @if($log->statut == 'success')
                                            <span class="badge bg-success">Succès</span>
                                        @elseif($log->statut == 'error')
                                            <span class="badge bg-danger">Erreur</span>
                                        @else
                                            <span class="badge bg-warning">{{ $log->statut }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->date_execution->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if($log->details)
                                            <button class="btn btn-sm btn-info" onclick="alert('{{ $log->details }}')">
                                                <i class="fas fa-info-circle"></i> Détails
                                            </button>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $cronLogs->links() }}
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Aucune tâche cron n'a encore été exécutée. Les logs apparaîtront ici une fois les tâches automatiques configurées.
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
    </div>
</div>
@endsection