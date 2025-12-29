<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function view(User $user, Vehicle $vehicle)
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return $user->organization_id && $user->organization_id === $vehicle->organization_id;
    }

    public function update(User $user, Vehicle $vehicle)
    {
        return $this->view($user, $vehicle) && (method_exists($user, 'hasRole') ? $user->hasRole('organization_admin') : true);
    }
}
