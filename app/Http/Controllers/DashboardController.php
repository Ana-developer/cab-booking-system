<?php

namespace App\Http\Controllers;

use App\Models\CarBooking;
use App\Models\Billing;
   use Carbon\Carbon;

class DashboardController extends Controller
{

public function index()
{
    $totalBookings = CarBooking::count();

    $todayBookings = CarBooking::whereDate('created_at', Carbon::today())->count();

    $totalRevenue = Billing::where('status', 'paid')
                            ->sum('total_amount');

    $pendingPayments = Billing::where('status', 'pending')
                              ->sum('balance_amount');

    $totalInvoices = Billing::count();

    $recentInvoices = Billing::latest()
                             ->take(5)
                             ->get();

    // Revenue chart (Last 7 days)
    $revenues = Billing::where('status', 'paid')
        ->whereDate('created_at', '>=', Carbon::now()->subDays(6))
        ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return view('dashboard', compact(
        'totalBookings',
        'todayBookings',
        'totalRevenue',
        'pendingPayments',
        'totalInvoices',
        'recentInvoices',
        'revenues'
    ));

    
}



}