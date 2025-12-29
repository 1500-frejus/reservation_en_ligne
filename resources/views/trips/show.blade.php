@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $trip->name }}</h1>
    <p>{{ $trip->origin }} → {{ $trip->destination }}</p>

    <h3>Horaires</h3>
    @foreach($trip->schedules as $schedule)
        <div>
            <strong>{{ $schedule->departure_at->format('d/m/Y H:i') }}</strong>
            <span>{{ number_format($schedule->price,2) }} €</span>
            <a href="{{ route('bookings.create', $schedule->id) }}">Réserver</a>
        </div>
    @endforeach
</div>
@endsection
