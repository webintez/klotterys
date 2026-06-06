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
}
