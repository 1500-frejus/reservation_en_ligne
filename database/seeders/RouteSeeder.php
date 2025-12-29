<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgId = \App\Models\Organization::first()?->id ?? null;

        Route::create([
            'depart' => 'Paris',
            'arrivee' => 'Lyon',
            'horaire' => '08:00:00',
            'duree_minutes' => 120,
            'prix' => 45.00,
            'organization_id' => $orgId,
        ]);

        Route::create([
            'depart' => 'Paris',
            'arrivee' => 'Marseille',
            'horaire' => '09:30:00',
            'duree_minutes' => 240,
            'prix' => 75.00,
            'organization_id' => $orgId,
        ]);

        Route::create([
            'depart' => 'Lyon',
            'arrivee' => 'Marseille',
            'horaire' => '14:00:00',
            'duree_minutes' => 90,
            'prix' => 35.00,
            'organization_id' => $orgId,
        ]);

        Route::create([
            'depart' => 'Paris',
            'arrivee' => 'Nice',
            'horaire' => '07:15:00',
            'duree_minutes' => 300,
            'prix' => 85.00,
            'organization_id' => $orgId,
        ]);

        Route::create([
            'depart' => 'Marseille',
            'arrivee' => 'Nice',
            'horaire' => '16:45:00',
            'duree_minutes' => 60,
            'prix' => 25.00,
            'organization_id' => $orgId,
        ]);
    }
}
