<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasOrganization
{
    /**
     * Redirects users without organization to a setup page (unless super admin).
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $next($request);
        }

        if (empty($user->organization_id)) {
            // For now, abort with 403 to make the condition obvious during development
            abort(403, 'User not attached to an organization.');
        }

        return $next($request);
    }
}
