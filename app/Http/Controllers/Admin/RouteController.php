<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::withCount('bookings')->paginate(15);
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'depart' => 'required|string|max:255',
            'arrivee' => 'required|string|max:255',
            'horaire' => 'required|date_format:H:i',
            'duree_minutes' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);

        Route::create($data);

        return redirect()->route('admin.routes.index')->with('success', 'Trajet créé avec succès.');
    }

    public function show(Route $route)
    {
        $route->load('bookings.payment');
        return view('admin.routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $data = $request->validate([
            'depart' => 'required|string|max:255',
            'arrivee' => 'required|string|max:255',
            'horaire' => 'required|date_format:H:i',
            'duree_minutes' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
        ]);

        $route->update($data);

        return redirect()->route('admin.routes.index')->with('success', 'Trajet mis à jour avec succès.');
    }

    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Trajet supprimé avec succès.');
    }
}
