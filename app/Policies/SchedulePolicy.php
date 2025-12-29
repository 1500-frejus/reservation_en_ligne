<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function view(User $user, Schedule $schedule)
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return $user->organization_id && $user->organization_id === $schedule->organization_id;
    }

    public function update(User $user, Schedule $schedule)
    {
        return $this->view($user, $schedule) && (method_exists($user, 'hasRole') ? $user->hasRole('organization_admin') : true);
    }
}
