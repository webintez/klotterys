<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminSettingController extends Controller
{
    // Show settings form
    public function edit()
    {
        $setting = WebsiteSetting::first();
        if (!$setting) {
            $setting = WebsiteSetting::create([
                'qr_code' => 'images/qr_code.jpeg',
                'upi_id' => '9369873638-t50f@ybl',
                'registration_fee' => 3150.00,
                'bank_name' => 'State Bank of India',
                'bank_account_name' => 'Kerala State Lottery',
                'bank_account_no' => '53845623856',
                'bank_ifsc' => 'SBIN0030466',
            ]);
        }
        return view('admin.settings.edit', compact('setting'));
    }

    // Update settings
    public function update(Request $request)
    {
        $setting = WebsiteSetting::first();
        if (!$setting) {
            $setting = new WebsiteSetting();
        }

        $request->validate([
            'upi_id' => 'required|string|max:100',
            'qr_code' => 'nullable|file|image|max:10240', // Max 10MB
            'registration_fee' => 'required|numeric|min:0',
            'bank_name' => 'required|string|max:150',
            'bank_account_name' => 'required|string|max:150',
            'bank_account_no' => 'required|string|max:100',
            'bank_ifsc' => 'required|string|max:50',
        ]);

        $setting->upi_id = $request->input('upi_id');
        $setting->registration_fee = $request->input('registration_fee');
        $setting->bank_name = $request->input('bank_name');
        $setting->bank_account_name = $request->input('bank_account_name');
        $setting->bank_account_no = $request->input('bank_account_no');
        $setting->bank_ifsc = $request->input('bank_ifsc');

        if ($request->hasFile('qr_code')) {
            // Delete old QR code if it exists and is custom
            if ($setting->qr_code && $setting->qr_code !== 'images/qr_code.jpeg') {
                $oldPath = public_path($setting->qr_code);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('qr_code');
            $filename = 'qr_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $setting->qr_code = 'uploads/settings/' . $filename;
        }

        $setting->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Website settings updated successfully.');
    }
}
