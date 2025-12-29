@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bell"></i> VoyageExpress - {{ $type }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>{{ $type }}</strong>
                    </div>

                    <div class="notification-content">
                        <p>{{ $contenu }}</p>
                    </div>

                    <hr>

                    <div class="text-center">
                        <p class="text-muted mb-2">Cordialement,</p>
                        <p class="text-muted mb-0"><strong>Équipe VoyageExpress</strong></p>
                        <p class="text-muted small">Service de transport de voyageurs</p>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">
                        Cet email a été envoyé automatiquement par VoyageExpress.
                        Ne pas répondre à cet email.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection