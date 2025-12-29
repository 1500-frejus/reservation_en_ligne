<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $current = auth()->user();

        // By default compute global stats, but if user belongs to an organization
        // and is not a super_admin, scope counts to that organization.
        if ($current && method_exists($current, 'hasRole') && ! $current->hasRole('super_admin') && $current->organization_id) {
            $orgId = $current->organization_id;
            $stats = [
                'routes' => Route::where('organization_id', $orgId)->count(),
                'bookings' => Booking::where('organization_id', $orgId)->count(),
                'users' => User::where('organization_id', $orgId)->count(),
                // Protect revenue query in case older DBs don't have organization_id on payments
                'revenue' => (function () use ($orgId) {
                    $q = Payment::where('statut', 'paid');
                    if (Schema::hasColumn('payments', 'organization_id')) {
                        $q->where('organization_id', $orgId);
                    }
                    return $q->sum('montant');
                })(),
            ];

            $recentBookings = Booking::with('route', 'payment')
                ->where('organization_id', $orgId)
                ->latest()
                ->take(10)
                ->get();
        } elseif ($current && ! method_exists($current, 'hasRole') && $current->organization_id) {
            // fallback if roles package is not available
            $orgId = $current->organization_id;
            $stats = [
                'routes' => Route::where('organization_id', $orgId)->count(),
                'bookings' => Booking::where('organization_id', $orgId)->count(),
                'users' => User::where('organization_id', $orgId)->count(),
                'revenue' => (function () use ($orgId) {
                    $q = Payment::where('statut', 'paid');
                    if (Schema::hasColumn('payments', 'organization_id')) {
                        $q->where('organization_id', $orgId);
                    }
                    return $q->sum('montant');
                })(),
            ];

            $recentBookings = Booking::with('route', 'payment')
                ->where('organization_id', $orgId)
                ->latest()
                ->take(10)
                ->get();
        } else {
            $stats = [
                'routes' => Route::count(),
                'bookings' => Booking::count(),
                'users' => User::count(),
                'revenue' => Payment::where('statut', 'paid')->sum('montant'),
            ];

            $recentBookings = Booking::with('route', 'payment')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}