<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Payment;
use App\Models\CronLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailyBookings()
    {
        $dailyBookings = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('admin.reports.daily-bookings', compact('dailyBookings'));
    }

    public function popularRoutes()
    {
        $popularRoutes = Route::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.popular-routes', compact('popularRoutes'));
    }

    public function monthlyRevenue()
    {
        $monthlyRevenue = Payment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(montant) as total')
            ->where('statut', 'paid')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.reports.monthly-revenue', compact('monthlyRevenue'));
    }

    public function cronLogs()
    {
        $cronLogs = CronLog::orderBy('date_execution', 'desc')->paginate(20);
        return view('admin.reports.cron-logs', compact('cronLogs'));
    }
}
