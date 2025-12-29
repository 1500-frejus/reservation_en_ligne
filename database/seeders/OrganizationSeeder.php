<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Organization::factory()->create([
            'name' => 'Agence Demo',
            'slug' => 'agence-demo',
            'status' => 'active',
            'contact_email' => 'contact@agence-demo.test',
        ]);
    }
}
