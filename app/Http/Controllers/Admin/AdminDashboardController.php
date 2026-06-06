<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DrawResult;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        list($startDate, $endDate, $filter) = $this->getDateRange($request);

        // Calculate total revenue for selected date range
        $totalRevenue = Booking::whereIn('status', ['paid', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // Calculate total tickets sold for selected date range
        $paidBookings = Booking::whereIn('status', ['paid', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        $totalTicketsSold = 0;
        foreach ($paidBookings as $booking) {
            $tickets = array_filter(explode(',', $booking->tickets));
            $totalTicketsSold += count($tickets);
        }

        // Count statuses for selected date range
        $paidCount = Booking::whereIn('status', ['paid', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $pendingCount = Booking::whereIn('status', ['pending_payment', 'pending'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // General stats for selected date range
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalDraws = DrawResult::count();

        // Recent bookings for selected date range
        $recentBookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTicketsSold',
            'paidCount',
            'pendingCount',
            'totalBookings',
            'totalDraws',
            'recentBookings',
            'filter'
        ));
    }

    /**
     * Compute start and end carbon dates based on filter code.
     */
    private function getDateRange(Request $request)
    {
        $filter = $request->input('date_filter', 'today');
        $startDate = null;
        $endDate = null;

        switch ($filter) {
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->subYear()->endOfYear();
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                } else {
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    $filter = 'today';
                }
                break;
            case 'today':
            default:
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                $filter = 'today';
                break;
        }

        return [$startDate, $endDate, $filter];
    }
}
