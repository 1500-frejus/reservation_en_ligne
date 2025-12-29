<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgId = \App\Models\Organization::first()?->id ?? null;

        Vehicle::create([
            'immatriculation' => 'AB-123-CD',
            'type_id' => 1, // Bus
            'capacite' => 50,
            'statut' => 'active',
            'organization_id' => $orgId,
        ]);

        Vehicle::create([
            'immatriculation' => 'EF-456-GH',
            'type_id' => 2, // Minibus
            'capacite' => 20,
            'statut' => 'active',
            'organization_id' => $orgId,
        ]);

        Vehicle::create([
            'immatriculation' => 'IJ-789-KL',
            'type_id' => 3, // Taxi
            'capacite' => 4,
            'statut' => 'active',
            'organization_id' => $orgId,
        ]);

        Vehicle::create([
            'immatriculation' => 'MN-012-OP',
            'type_id' => 1, // Bus
            'capacite' => 45,
            'statut' => 'maintenance',
            'organization_id' => $orgId,
        ]);
    }
}
