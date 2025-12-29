<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use App\Models\Route;
use App\Policies\RoutePolicy;
use App\Models\Organization;
use App\Policies\OrganizationPolicy;
use App\Models\Schedule;
use App\Policies\SchedulePolicy;
use App\Models\Booking;
use App\Policies\BookingPolicy;
use App\Models\Vehicle;
use App\Policies\VehiclePolicy;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure compatibility with older MySQL default index length
        Schema::defaultStringLength(191);
        // Register model policies
        Gate::policy(Route::class, RoutePolicy::class);
        Gate::policy(Organization::class, OrganizationPolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
        Gate::policy(Vehicle::class, VehiclePolicy::class);
        Gate::policy(Ticket::class, TicketPolicy::class);

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
