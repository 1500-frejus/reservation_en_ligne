<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class OrganizationScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        try {
            $user = Auth::user();
        } catch (\Throwable $e) {
            $user = null;
        }

        if (!$user) {
            // no user, don't apply scope (for public resources)
            return;
        }

        // If user is super admin, do not scope
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return;
        }

        // Fallback: check legacy role_id == 1
        if (property_exists($user, 'role_id') && $user->role_id == 1) {
            return;
        }

        if (!empty($user->organization_id)) {
            $builder->where($model->getTable() . '.organization_id', $user->organization_id);
        }
    }
}
