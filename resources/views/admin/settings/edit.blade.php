@extends('layouts.admin')

@section('title', 'Website Settings')

@section('content')
<div class="content-header">
  <div class="content-title">
    <h1>Website Settings</h1>
    <p>Manage global configurations like UPI payments and QR codes.</p>
  </div>
</div>

<div class="admin-card" style="max-width: 600px; margin-top: 1.5rem;">
  <div class="admin-card-header">
    <h2>Update UPI & Payment Settings</h2>
  </div>
  <div class="admin-card-body">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label class="form-label" for="upi_id">UPI ID</label>
        <input type="text" name="upi_id" id="upi_id" class="form-control" placeholder="e.g. 9369873638-t50f@ybl" required value="{{ old('upi_id', $setting->upi_id) }}">
        <small style="color: #6c757d; margin-top: 0.25rem; display: block;">This UPI ID will be used for both QR and direct deep linking on checkout and claim screens.</small>
      </div>

      <div class="form-group" style="margin-top: 1.5rem;">
        <label class="form-label" for="qr_code">Payment QR Code Image</label>
        
        @if($setting->qr_code)
          <div style="margin-bottom: 1rem; background: #f8f9fa; padding: 1rem; border-radius: 8px; border: 1px solid #e9ecef; display: inline-block;">
            <p style="font-size: 0.8rem; font-weight: 600; color: #495057; margin-bottom: 0.5rem;">Current QR Code Preview:</p>
            <img src="{{ asset($setting->qr_code) }}" alt="QR Code Preview" style="max-width: 150px; display: block; border-radius: 6px; border: 1px solid #dee2e6;">
          </div>
        @endif

        <input type="file" name="qr_code" id="qr_code" class="form-control" accept="image/*">
        <small style="color: #6c757d; margin-top: 0.25rem; display: block;">Upload a square image of your UPI QR code. Max size: 10MB.</small>
      </div>

      <div style="margin-top: 2rem;">
        <button type="submit" class="btn-admin">Save Settings</button>
      </div>
    </form>
  </div>
</div>
@endsection
