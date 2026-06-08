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
        $ticket = strtoupper(trim($request->input('ticket')));
        $mobile = trim($request->input('mobile'));

        if (!$ticket || !$mobile) {
            return response()->json(['status' => 'not_found']);
        }

        // Step 1: Verify the ticket was purchased with this mobile number
        $booking = \App\Models\Booking::where('mobile', $mobile)->get();
        $ticketFound = false;
        foreach ($booking as $b) {
            $tickets = json_decode($b->tickets, true);
            if (is_array($tickets) && in_array($ticket, array_map('strtoupper', $tickets))) {
                $ticketFound = true;
                break;
            }
            // Also handle comma-separated string storage
            if (is_string($b->tickets)) {
                $ticketList = array_map('trim', explode(',', $b->tickets));
                if (in_array($ticket, array_map('strtoupper', $ticketList))) {
                    $ticketFound = true;
                    break;
                }
            }
        }

        if (!$ticketFound) {
            // Ticket and mobile do not match any purchase record
            return response()->json(['status' => 'not_found']);
        }

        // Step 2: Check if this ticket is a winning number
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
                'status' => 'won',
                'prize'  => $prizeAmount,
                'tax_amount' => $draw->tax_amount,
            ]);
        }

        // Ticket was purchased with the correct mobile but did not win
        return response()->json(['status' => 'no_win']);
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

    // Generate dynamic certificate image on the fly
    public function certificateImage(Request $request)
    {
        $ticket = $request->input('ticket');
        $mobile = $request->input('mobile');

        if (!$ticket || !$mobile) {
            abort(404);
        }

        // Query database to see if this ticket matches any winning_number in draw_results
        $draw = DrawResult::where('winning_number', $ticket)->first();
        
        // Find booking
        $booking = \App\Models\Booking::where('mobile', $mobile)
            ->where('tickets', 'like', "%{$ticket}%")
            ->first();

        // Fallback for mock/simulation wins
        $fullname = $booking ? $booking->fullname : 'Winner Player';
        $winningAmount = $draw ? $draw->winning_amount : '₹15,00,000';
        $drawDate = $draw ? \Carbon\Carbon::parse($draw->draw_date)->format('d-m-Y g:i A') : now()->format('d-m-Y 3:00 PM');

        // Clean amount for template (remove ₹ and spaces)
        $cleanAmount = str_replace(['₹', ' ', 'Rs.', 'Rs'], '', $winningAmount);

        // Load base template image
        $sourcePath = public_path('images/certificate_base.jpg');
        if (!file_exists($sourcePath)) {
            abort(404, "Base certificate image not found");
        }

        $im = imagecreatefromjpeg($sourcePath);
        if (!$im) {
            abort(500, "Failed to load base certificate image");
        }

        // Sample background color near the fields (cream color) to mask placeholders
        $bgRgb = imagecolorat($im, 450, 430);
        $r = ($bgRgb >> 16) & 0xFF;
        $g = ($bgRgb >> 8) & 0xFF;
        $b = $bgRgb & 0xFF;

        $maskColor = imagecolorallocate($im, $r, $g, $b);
        $lineColor = imagecolorallocate($im, 100, 100, 100); // dark grey for underlines
        $textColor = imagecolorallocate($im, 43, 43, 43); // dark grey/black for text

        // Fonts
        $fontBold = public_path('fonts/timesbd.ttf');
        if (!file_exists($fontBold)) {
            $fontBold = public_path('fonts/arial.ttf'); // fallback
        }

        // Let's define the rectangles to mask out the old text
        $masks = [
            'name' => [460, 430, 1170, 480],
            'ticket' => [640, 580, 1045, 630],
            'amount' => [715, 655, 1045, 710],
            'date' => [720, 735, 1070, 790]
        ];

        foreach ($masks as $rCoords) {
            imagefilledrectangle($im, $rCoords[0], $rCoords[1], $rCoords[2], $rCoords[3], $maskColor);
        }

        // Let's draw the lines where text goes
        $lines = [
            'name' => [460, 478, 1170, 478],
            'ticket' => [640, 626, 1045, 626],
            'amount' => [715, 706, 1045, 706],
            'date' => [720, 786, 1070, 786]
        ];

        foreach ($lines as $lCoords) {
            imageline($im, $lCoords[0], $lCoords[1], $lCoords[2], $lCoords[3], $lineColor);
        }

        // Draw centered text on lines (font size 24 for high-res 1536x1024 template)
        $this->drawCenteredTextOnImage($im, strtoupper($fullname), $fontBold, 24, $textColor, 460, 1170, 470);
        $this->drawCenteredTextOnImage($im, strtoupper($ticket), $fontBold, 24, $textColor, 640, 1045, 618);
        $this->drawCenteredTextOnImage($im, $cleanAmount, $fontBold, 24, $textColor, 715, 1045, 698);
        $this->drawCenteredTextOnImage($im, $drawDate, $fontBold, 24, $textColor, 720, 1070, 778);

        // Capture image output in buffer
        ob_start();
        imagepng($im);
        $imageData = ob_get_clean();

        imagedestroy($im);

        return response($imageData)->header('Content-Type', 'image/png');
    }

    private function drawCenteredTextOnImage($im, $text, $font, $size, $color, $x1, $x2, $y)
    {
        $bbox = imagettfbbox($size, 0, $font, $text);
        $textWidth = abs($bbox[2] - $bbox[0]);
        $lineWidth = $x2 - $x1;
        $x = $x1 + ($lineWidth - $textWidth) / 2;
        imagettftext($im, $size, 0, $x, $y, $color, $font, $text);
    }
}
