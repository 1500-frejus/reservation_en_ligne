@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-calendar"></i> Réservations par Jour</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Évolution des réservations sur les 30 derniers jours</h5>
            </div>
            <div class="card-body">
                @if($dailyBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nombre de réservations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyBookings as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                                    <td>{{ $day->count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <canvas id="dailyBookingsChart" width="400" height="200"></canvas>
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
@if($dailyBookings->count() > 0)
const ctx = document.getElementById('dailyBookingsChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            @foreach($dailyBookings->reverse() as $day)
                '{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}',
            @endforeach
        ],
        datasets: [{
            label: 'Réservations',
            data: [
                @foreach($dailyBookings->reverse() as $day)
                    {{ $day->count }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
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