<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with('schedules')->get();
        return view('trips.index', compact('trips'));
    }

    public function show(Trip $trip)
    {
        $trip->load('schedules');
        return view('trips.show', compact('trip'));
    }
}
