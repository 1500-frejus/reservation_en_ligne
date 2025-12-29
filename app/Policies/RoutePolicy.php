<?php

namespace App\Policies;

use App\Models\Route;
use App\Models\User;

class RoutePolicy
{
    public function view(User $user, Route $route)
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return $user->organization_id && $user->organization_id === $route->organization_id;
    }

    public function update(User $user, Route $route)
    {
        return $this->view($user, $route);
    }

    public function delete(User $user, Route $route)
    {
        return $this->view($user, $route);
    }
}
