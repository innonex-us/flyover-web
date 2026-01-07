<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Visa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Counts
        $totalPackages = Package::count();
        $activeVisas = Visa::count(); // Assuming all visas in DB are 'active' services effectively
        $totalBookings = Booking::count();
        
        // Revenue (sum of total_amount for confirmed/completed bookings)
        // Assuming total_amount exists and status confirmed/completed means money in/expected.
        // Adjust logic based on payment_status if needed (e.g., only 'paid'). 
        // For now, let's use confirmed/completed status as metric.
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_amount');

        // Recent Bookings
        $recentBookings = Booking::with(['user', 'payable'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Booking Stats
        $newBookingsCount = Booking::where('status', 'pending')->count();
        $confirmedBookingsCount = Booking::where('status', 'confirmed')->count();

        return view('admin.dashboard', compact(
            'totalPackages',
            'activeVisas',
            'totalBookings',
            'totalRevenue',
            'recentBookings',
            'newBookingsCount',
            'confirmedBookingsCount'
        ));
    }
}
