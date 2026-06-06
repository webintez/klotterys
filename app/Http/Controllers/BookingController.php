<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;

class BookingController extends Controller
{
    // Show book ticket form
    public function book(Request $request)
    {
        return view('book-ticket');
    }

    // Submit booking details
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|min:2',
            'mobile' => 'required|string|size:10',
            'state' => 'required|string',
            'pincode' => 'required|string|size:6',
            'ticketno' => 'required|string',
        ]);

        $ticketParam = $request->input('ticketno');
        $tickets = array_filter(explode(',', $ticketParam));
        
        $totalPrice = 0;
        foreach ($tickets as $ticket) {
            $prefix = substr($ticket, 0, 2);
            if ($prefix === 'VL') {
                $totalPrice += 500;
            } elseif ($prefix === 'SL') {
                $totalPrice += 149;
            } else {
                $totalPrice += 40;
            }
        }

        // Save booking to DB
        $booking = Booking::create([
            'fullname' => $request->input('fullname'),
            'mobile' => $request->input('mobile'),
            'state' => $request->input('state'),
            'pincode' => $request->input('pincode'),
            'tickets' => $ticketParam,
            'total_price' => $totalPrice,
            'status' => 'pending_payment',
        ]);

        // Redirect to dynamic simulated payment page
        return view('pay', ['booking' => $booking]);
    }

    // Process payment screenshot upload
    public function paySuccess(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'screenshot' => 'required|file|image|max:10240', // Max 10MB (10240 KB)
        ]);

        $booking = Booking::findOrFail($request->input('booking_id'));
        
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/screenshots'), $filename);
            $screenshotPath = 'uploads/screenshots/' . $filename;
        }

        $booking->update([
            'status' => 'paid',
            'screenshot' => $screenshotPath,
        ]);

        return redirect()->route('track-order')->with('success', 'Payment screenshot uploaded successfully! Your tickets are registered and payment is being processed.');
    }

    // Show track order view
    public function track()
    {
        return view('track-order');
    }

    // AJAX track search
    public function search(Request $request)
    {
        $ticket = $request->input('ticket');
        $mobile = $request->input('mobile');

        if (!$ticket || !$mobile) {
            return response()->json(['success' => false, 'message' => 'Missing inputs']);
        }

        $booking = Booking::where('mobile', $mobile)
            ->where('tickets', 'like', '%' . $ticket . '%')
            ->first();

        if ($booking) {
            return response()->json([
                'success' => true,
                'status' => $booking->status,
                'tracking_number' => $booking->tracking_number,
            ]);
        }

        return response()->json(['success' => false]);
    }
}
