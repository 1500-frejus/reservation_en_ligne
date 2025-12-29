@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-euro-sign"></i> Revenus Mensuels</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Évolution des revenus sur les 12 derniers mois</h5>
            </div>
            <div class="card-body">
                @if($monthlyRevenue->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Revenus totaux</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyRevenue as $month)
                                <tr>
                                    <td>{{ $month->year }}-{{ str_pad($month->month, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td><strong>{{ number_format($month->total, 2) }}€</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
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
@if($monthlyRevenue->count() > 0)
const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            @foreach($monthlyRevenue->reverse() as $month)
                '{{ $month->year }}-{{ str_pad($month->month, 2, '0', STR_PAD_LEFT) }}',
            @endforeach
        ],
        datasets: [{
            label: 'Revenus (€)',
            data: [
                @foreach($monthlyRevenue->reverse() as $month)
                    {{ $month->total }},
                @endforeach
            ],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + '€';
                    }
                }
            }
        }
    }
});
@endif
</script>
@endsection