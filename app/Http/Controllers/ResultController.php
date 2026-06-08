<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DrawResult;

class ResultController extends Controller
{
    // Show draw results with filters
    public function index(Request $request)
    {
        $query = DrawResult::query();

        if ($request->filled('date')) {
            $query->whereDate('draw_date', $request->input('date'));
        }

        if ($request->filled('name')) {
            $query->where('lottery_name', $request->input('name'));
        }

        $results = $query->orderBy('draw_date', 'desc')->get();

        return view('results', compact('results'));
    }

    // AJAX route to check if a ticket has won
    public function check(Request $request)
    {
        $ticket = $request->input('ticket');
        $mobile = $request->input('mobile');

        if (!$ticket || !$mobile) {
            return response()->json(['won' => false, 'message' => 'Missing inputs']);
        }

        // Query database to see if this ticket matches any winning_number in draw_results
        $draw = DrawResult::where('winning_number', $ticket)->first();

        if ($draw) {
            $amount = $draw->winning_amount;
            if (!$amount) {
                if ($draw->prize_category === '1st Prize') {
                    $amount = '₹5,000';
                } elseif ($draw->prize_category === '2nd Prize') {
                    $amount = '₹2,500';
                } elseif ($draw->prize_category === '3rd Prize') {
                    $amount = '₹1,000';
                } else {
                    $amount = '₹5,000';
                }
            }

            // Ensure amount starts with ₹ for consistent UI display if it's purely numeric
            if (is_numeric(str_replace([',', ' '], '', $amount))) {
                $amount = '₹' . number_format((float)str_replace([',', ' '], '', $amount));
            }

            $prizeAmount = $amount . ' (' . $draw->prize_category . ')';
            return response()->json([
                'won' => true,
                'prize' => $prizeAmount,
                'tax_amount' => $draw->tax_amount,
            ]);
        }

        // Pseudo-random fallback simulation (25% win rate)
        $hash = 0;
        for ($i = 0; i < strlen($ticket); $i++) {
            $hash = ord($ticket[$i]) + (($hash << 5) - $hash);
        }
        $won = (abs($hash) % 4) === 0;

        if ($won) {
            $prize = (abs($hash) % 3 === 0) ? '₹5,000' : ((abs($hash) % 3 === 1) ? '₹1,000' : '₹500');
            return response()->json([
                'won' => true,
                'prize' => $prize,
            ]);
        }

        return response()->json(['won' => false]);
    }

    // Submit a prize claim
    public function claim(Request $request)
    {
        $request->validate([
            'ticket' => 'required|string',
            'mobile' => 'required|string',
            'screenshot' => 'required|file|image|max:10240', // Max 10MB
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/screenshots'), $filename);
            $screenshotPath = 'uploads/screenshots/' . $filename;
        }

        $setting = \App\Models\WebsiteSetting::first();
        $fee = $setting ? $setting->registration_fee : 3150.00;

        // Store claim in DB
        \App\Models\PrizeClaim::create([
            'ticket_number' => $request->input('ticket'),
            'mobile' => $request->input('mobile'),
            'registration_fee' => $fee,
            'screenshot' => $screenshotPath,
            'status' => 'paid', // Marked as paid on submit
        ]);

        return response()->json(['success' => true, 'message' => 'Claim submitted successfully!']);
    }

    // Show winner landing page
    public function winner(Request $request)
    {
        $ticket = $request->input('ticket');
        $mobile = $request->input('mobile');

        if (!$ticket || !$mobile) {
            return redirect()->route('results');
        }

        // Query database to see if this ticket matches any winning_number in draw_results
        $draw = DrawResult::where('winning_number', $ticket)->first();
        
        // Find booking
        $booking = \App\Models\Booking::where('mobile', $mobile)
            ->where('tickets', 'like', "%{$ticket}%")
            ->first();

        // Fallback for mock/simulation wins
        $fullname = $booking ? $booking->fullname : 'Winner Player';
        $prizeCategory = $draw ? $draw->prize_category : '1st Prize';
        $winningAmount = $draw ? $draw->winning_amount : '₹15,00,000';
        $drawDate = $draw ? \Carbon\Carbon::parse($draw->draw_date)->format('d-m-Y H:i A') : now()->format('d-m-Y 3:00 PM');
        $lotteryName = $draw ? $draw->lottery_name : 'Kerala Bumper';

        // Retrieve settings for payment claim popup
        $setting = \App\Models\WebsiteSetting::first();
        $qrCode = $setting && $setting->qr_code ? asset($setting->qr_code) : asset('images/qr_code.jpeg');
        $upiId = $setting ? $setting->upi_id : '9369873638-t50f@ybl';
        $registrationFee = $setting ? $setting->registration_fee : 3150.00;
        $bankName = $setting ? $setting->bank_name : 'State Bank of India';
        $bankAccountName = $setting ? $setting->bank_account_name : 'Kerala State Lottery';
        $bankAccountNo = $setting ? $setting->bank_account_no : '53845623856';
        $bankIfsc = $setting ? $setting->bank_ifsc : 'SBIN0030466';

        // Fetch other results of the same date
        $drawDateOnly = $draw ? $draw->draw_date : now()->toDateString();
        $otherResults = DrawResult::whereDate('draw_date', $drawDateOnly)
            ->orderBy('prize_category', 'asc')
            ->get();

        return view('winner', compact(
            'ticket',
            'mobile',
            'fullname',
            'prizeCategory',
            'winningAmount',
            'drawDate',
            'lotteryName',
            'qrCode',
            'upiId',
            'registrationFee',
            'bankName',
            'bankAccountName',
            'bankAccountNo',
            'bankIfsc',
            'otherResults'
        ));
    }
}
