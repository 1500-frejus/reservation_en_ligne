<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking)
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        // org admins and agents can view bookings for their organization
        if ($user->organization_id && $user->organization_id === $booking->organization_id) {
            return method_exists($user, 'hasRole') ? ($user->hasRole('organization_admin') || $user->hasRole('agent')) : true;
        }

        return false;
    }

    public function update(User $user, Booking $booking)
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        return $user->organization_id && $user->organization_id === $booking->organization_id && (method_exists($user, 'hasRole') ? $user->hasRole('organization_admin') : true);
    }
}
