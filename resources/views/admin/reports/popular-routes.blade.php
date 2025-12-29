@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-route"></i> Trajets Populaires</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Top 10 des trajets les plus réservés</h5>
            </div>
            <div class="card-body">
                @if($popularRoutes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Rang</th>
                                    <th>Trajet</th>
                                    <th>Horaire</th>
                                    <th>Prix</th>
                                    <th>Nombre de réservations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularRoutes as $index => $route)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>{{ $route->depart }} → {{ $route->arrivee }}</td>
                                    <td>{{ $route->horaire->format('H:i') }}</td>
                                    <td>{{ $route->prix }}€</td>
                                    <td><span class="badge bg-primary">{{ $route->bookings_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <canvas id="popularRoutesChart" width="400" height="200"></canvas>
                    </div>
                @else
                    <p class="text-muted">Aucune donnée disponible.</p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($popularRoutes->count() > 0)
const ctx = document.getElementById('popularRoutesChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($popularRoutes as $route)
                '{{ $route->depart }} → {{ $route->arrivee }}',
            @endforeach
        ],
        datasets: [{
            label: 'Nombre de réservations',
            data: [
                @foreach($popularRoutes as $route)
                    {{ $route->bookings_count }},
                @endforeach
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
@endif
</script>
@endsection