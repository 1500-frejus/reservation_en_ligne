<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

Route::get('/', [RouteController::class, 'index'])->name('home');

// Temporary dev/test routes removed for production readiness. If you need them,
// re-add guarded test routes behind a local-only or debug middleware.

Route::resource('routes', RouteController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/routes/{route}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/download-pdf', [BookingController::class, 'downloadPdf'])->name('bookings.download-pdf');
    Route::get('/bookings', [BookingController::class, 'history'])->name('bookings.history');

    // Notifications utilisateur
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', function (\App\Models\Notification $notification) {
        if ($notification->user_id == auth()->id() || $notification->user_id === null) {
            $notification->update(['statut' => 'read']);
        }
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    })->name('notifications.mark-read');
});

// Routes admin (protégées)
Route::middleware(['auth', \App\Http\Middleware\EnsureUserHasOrganization::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des trajets
    Route::resource('routes', AdminRouteController::class);

    // Gestion des véhicules
    Route::resource('vehicles', AdminVehicleController::class);

    // Gestion des utilisateurs
    Route::resource('users', AdminUserController::class);

    // Gestion des réservations
    Route::resource('bookings', AdminBookingController::class)->except(['create', 'store']);

    // Gestion des horaires
    Route::resource('schedules', AdminScheduleController::class);

    // Gestion des notifications
    Route::resource('notifications', AdminNotificationController::class);
    Route::post('notifications/{notification}/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/send-test', [AdminNotificationController::class, 'sendTest'])->name('notifications.send-test');

    // Rapports
    Route::get('reports/daily-bookings', [AdminReportController::class, 'dailyBookings'])->name('reports.daily-bookings');
    Route::get('reports/popular-routes', [AdminReportController::class, 'popularRoutes'])->name('reports.popular-routes');
    Route::get('reports/monthly-revenue', [AdminReportController::class, 'monthlyRevenue'])->name('reports.monthly-revenue');
    Route::get('reports/cron-logs', [AdminReportController::class, 'cronLogs'])->name('reports.cron-logs');
});

    // Organization admin routes (only org admins + super_admin)
    Route::middleware(['auth', \App\Http\Middleware\EnsureOrganizationAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('organization', [\App\Http\Controllers\Admin\OrganizationController::class, 'edit'])->name('organization.edit');
        Route::put('organization', [\App\Http\Controllers\Admin\OrganizationController::class, 'update'])->name('organization.update');
    });

    // Agents management
    Route::middleware(['auth', \App\Http\Middleware\EnsureOrganizationAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('agents', [\App\Http\Controllers\Admin\AgentController::class, 'index'])->name('agents.index');
        Route::get('agents/create', [\App\Http\Controllers\Admin\AgentController::class, 'create'])->name('agents.create');
        Route::post('agents', [\App\Http\Controllers\Admin\AgentController::class, 'store'])->name('agents.store');
        Route::delete('agents/{user}', [\App\Http\Controllers\Admin\AgentController::class, 'destroy'])->name('agents.destroy');
            // invitations
            Route::get('agents/invite', [\App\Http\Controllers\Admin\AgentInvitationController::class, 'create'])->name('agents.invite');
            Route::post('agents/invite', [\App\Http\Controllers\Admin\AgentInvitationController::class, 'store'])->name('agents.invite.store');
    });

require __DIR__.'/auth.php';

// Organization onboarding
Route::get('/organizations/register', [App\Http\Controllers\OrganizationOnboardingController::class, 'create'])->name('organizations.register');
Route::post('/organizations/register', [App\Http\Controllers\OrganizationOnboardingController::class, 'store'])->name('organizations.register.store');
Route::get('/organizations/register/success', [App\Http\Controllers\OrganizationOnboardingController::class, 'success'])->name('organizations.register.success');

// Invitation acceptance (public)
Route::get('/invitations/accept/{token}', function ($token) {
    $inv = App\Models\AgentInvitation::where('token', $token)->firstOrFail();
    if ($inv->accepted_at) {
        abort(410, 'Invitation already used');
    }
    return view('invitations.accept', ['invitation' => $inv]);
})->name('invitations.accept');

Route::post('/invitations/accept/{token}', function (\Illuminate\Http\Request $request, $token) {
    $inv = App\Models\AgentInvitation::where('token', $token)->firstOrFail();
    if ($inv->accepted_at) {
        abort(410, 'Invitation already used');
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = App\Models\User::create([
        'name' => $data['name'],
        'email' => $inv->email,
        'password' => Hash::make($data['password']),
        'organization_id' => $inv->organization_id,
    ]);

    if (method_exists($user, 'assignRole')) {
        try { $user->assignRole('agent'); } catch (\Throwable $e) { }
    }

    $inv->update(['accepted_at' => now()]);

    return redirect('/login')->with('success', 'Account created. Please log in.');
})->name('invitations.accept.post');
