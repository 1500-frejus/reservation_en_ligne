<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('type')->paginate(15);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $vehicleTypes = VehicleType::all();
        return view('admin.vehicles.create', compact('vehicleTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'immatriculation' => 'required|string|max:255|unique:vehicles',
            'type_id' => 'required|exists:vehicle_types,id',
            'capacite' => 'required|integer|min:1',
            'statut' => 'required|in:active,inactive,maintenance',
        ]);

        Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule créé avec succès.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('type');
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $vehicleTypes = VehicleType::all();
        return view('admin.vehicles.edit', compact('vehicle', 'vehicleTypes'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'immatriculation' => 'required|string|max:255|unique:vehicles,immatriculation,' . $vehicle->id,
            'type_id' => 'required|exists:vehicle_types,id',
            'capacite' => 'required|integer|min:1',
            'statut' => 'required|in:active,inactive,maintenance',
        ]);

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule mis à jour avec succès.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule supprimé avec succès.');
    }
}
