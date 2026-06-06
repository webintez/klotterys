<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DrawResult;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Calculate total revenue
        $totalRevenue = Booking::whereIn('status', ['paid', 'completed'])->sum('total_price');

        // Calculate total tickets sold
        $paidBookings = Booking::whereIn('status', ['paid', 'completed'])->get();
        $totalTicketsSold = 0;
        foreach ($paidBookings as $booking) {
            $tickets = array_filter(explode(',', $booking->tickets));
            $totalTicketsSold += count($tickets);
        }

        // Count statuses
        $paidCount = Booking::whereIn('status', ['paid', 'completed'])->count();
        $pendingCount = Booking::whereIn('status', ['pending_payment', 'pending'])->count();
        
        // General stats
        $totalBookings = Booking::count();
        $totalDraws = DrawResult::count();

        // Recent bookings
        $recentBookings = Booking::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTicketsSold',
            'paidCount',
            'pendingCount',
            'totalBookings',
            'totalDraws',
            'recentBookings'
        ));
    }
}
