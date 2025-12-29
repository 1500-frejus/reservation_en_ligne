@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trajets</h1>
    @foreach($trips as $trip)
        <div>
            <h3><a href="{{ route('trips.show', $trip->id) }}">{{ $trip->name }}</a></h3>
            <p>{{ $trip->origin }} → {{ $trip->destination }}</p>
        </div>
    @endforeach
</div>
@endsection
