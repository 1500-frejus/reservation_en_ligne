<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Route;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('route')->paginate(15);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $routes = Route::all();
        return view('admin.schedules.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'departure_at' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
            'seats_available' => 'required|integer|min:1',
        ]);

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Horaire créé avec succès.');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load('route', 'bookings');
        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $routes = Route::all();
        return view('admin.schedules.edit', compact('schedule', 'routes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'departure_at' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
            'seats_available' => 'required|integer|min:1',
        ]);

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Horaire modifié avec succès.');
    }

    public function destroy(Schedule $schedule)
    {
        // Vérifier s'il y a des réservations pour cet horaire
        if ($schedule->bookings()->count() > 0) {
            return redirect()->route('admin.schedules.index')->with('error', 'Impossible de supprimer cet horaire car il a des réservations associées.');
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Horaire supprimé avec succès.');
    }
}
