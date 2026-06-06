<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    // List bookings
    public function index(Request $request)
    {
        $query = Booking::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('tickets', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    // Edit booking form
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
    }

    // Update booking
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:pending_payment,paid,completed,cancelled',
            'tracking_number' => 'nullable|string|max:50',
        ]);

        $booking->update([
            'status' => $request->input('status'),
            'tracking_number' => $request->input('tracking_number'),
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    // Delete booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
