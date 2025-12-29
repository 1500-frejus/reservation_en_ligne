<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (!class_exists(\Spatie\Permission\Models\Role::class)) {
            // Spatie package not installed; skip creating roles.
            return;
        }

        $roles = ['super_admin', 'organization_admin', 'agent'];

        foreach ($roles as $r) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $r]);
        }

        // Assign super_admin to first user if present
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole('super_admin');
        }
    }
}
