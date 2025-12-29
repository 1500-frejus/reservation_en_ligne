<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleType::create(['name' => 'Bus']);
        VehicleType::create(['name' => 'Minibus']);
        VehicleType::create(['name' => 'Taxi']);
    }
}
