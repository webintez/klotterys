@extends('layouts.app')

@php
  $setting = \App\Models\WebsiteSetting::first();
  $qrCode = $setting && $setting->qr_code ? asset($setting->qr_code) : asset('images/qr_code.jpeg');
  $upiId = $setting ? $setting->upi_id : '9369873638-t50f@ybl';
@endphp

@section('title', 'Kerala State Lotteries | UPI Payment')

@section('styles')
  <style>
    body {
      background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%) !important;
      min-height: 100vh;
      color: #333333;
    }
    .pay-card {
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      max-width: 900px;
      width: 100%;
      padding: 3rem;
      margin: 40px auto;
    }
    .user-info-section {
      border-bottom: 2px solid #f1f3f5;
      padding-bottom: 1.5rem;
      margin-bottom: 2rem;
    }
    .user-info-title {
      font-size: 1.5rem;
      font-weight: 800;
      color: #0d1b2a;
      margin-bottom: 0.5rem;
    }
    .info-item {
      font-size: 1.05rem;
      color: #495057;
      margin-bottom: 0.4rem;
    }
    .info-item strong {
      color: #212529;
    }
    .tickets-section {
      margin-bottom: 1.5rem;
    }
    .section-label {
      font-weight: 700;
      color: #0d1b2a;
      font-size: 1.1rem;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .ticket-badge {
      display: inline-block;
      background: #007adf;
      color: #ffffff;
      padding: 0.4rem 1rem;
      border-radius: 8px;
      font-weight: 800;
      font-size: 0.95rem;
      margin-right: 0.5rem;
      margin-bottom: 0.5rem;
      border: 1px solid rgba(0, 122, 223, 0.2);
    }
    .amount-display {
      font-size: 1.5rem;
      font-weight: 800;
      color: #0d1b2a;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .amount-val {
      color: #28a745;
      font-size: 1.8rem;
    }
    .payment-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }
    @media (max-width: 768px) {
      .payment-grid {
        grid-template-columns: 1fr;
      }
    }
    .payment-box {
      background: #ffffff;
      border: 1.5px solid #e9ecef;
      border-radius: 16px;
      padding: 1.5rem;
      text-align: center;
      transition: all 0.25s ease;
    }
    .payment-box:hover {
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      border-color: #007adf;
    }
    .payment-box h4 {
      font-weight: 800;
      color: #0d1b2a;
      font-size: 1.1rem;
      margin-bottom: 1.25rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .qr-container {
      width: 150px;
      height: 150px;
      margin: 0 auto 1rem;
    }
    .qr-container img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .upi-btn-stack {
      display: flex;
      flex-direction: column;
      gap: 0.65rem;
    }
    .upi-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.65rem 1rem;
      border-radius: 8px;
      color: #ffffff;
      font-weight: 700;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s ease;
      border: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .upi-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      color: #ffffff;
    }
    .upi-gpay { background-color: #1e8e3e; }
    .upi-phonepe { background-color: #5f259f; }
    .upi-paytm { background-color: #00b9f5; }
    .upi-other { background-color: #374151; }

    .bank-details {
      text-align: left;
      font-size: 0.95rem;
      color: #495057;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }
    .bank-details p {
      margin: 0;
    }
    .bank-details strong {
      color: #212529;
    }

    .screenshot-section {
      background: #f8f9fa;
      border: 2px dashed #ced4da;
      border-radius: 16px;
      padding: 2rem;
      text-align: center;
      margin-top: 2rem;
      transition: all 0.25s ease;
    }
    .screenshot-section:focus-within {
      border-color: #007adf;
      background: #f1f8ff;
    }
    .screenshot-title {
      font-weight: 800;
      color: #0d1b2a;
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }
    .screenshot-sub {
      color: var(--text-muted);
      font-size: 0.85rem;
      margin-bottom: 1.5rem;
    }
    .btn-submit-proof {
      background: linear-gradient(45deg, #ff5722, #ff7043);
      color: #ffffff;
      font-weight: 800;
      font-size: 1.1rem;
      border: none;
      padding: 0.9rem 2.5rem;
      border-radius: 50px;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(255, 87, 34, 0.4);
      transition: all 0.25s ease;
      margin-top: 1.5rem;
      width: 100%;
      max-width: 350px;
    }
    .btn-submit-proof:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 87, 34, 0.6);
    }
  </style>
@endsection

@section('content')
  <div class="container d-flex align-items-center justify-content-center">
    <div class="pay-card">
      
      <!-- User Info Block -->
      <div class="user-info-section">
        <div class="user-info-title">{{ $booking->fullname }}</div>
        <div class="info-item"><strong>Mobile:</strong> {{ $booking->mobile }}</div>
        <div class="info-item"><strong>State:</strong> {{ $booking->state }}</div>
        <div class="info-item"><strong>Pin:</strong> {{ $booking->pincode }}</div>
      </div>

      <!-- Selected Tickets -->
      <div class="tickets-section">
        <div class="section-label">🏷️ Selected Tickets:</div>
        <div>
          @foreach(explode(',', $booking->tickets) as $t)
            <span class="ticket-badge">{{ $t }}</span>
          @endforeach
        </div>
      </div>

      <!-- Total Amount -->
      <div class="amount-display">
        💰 Total Amount: <span class="amount-val">₹{{ number_format($booking->total_price) }}</span>
      </div>

      <!-- Payment Columns -->
      <h3 style="font-weight: 800; color: #0d1b2a; text-align: center; margin-bottom: 1.5rem; text-transform: uppercase;">Payment Details</h3>
      
      <div class="payment-grid">
        <!-- 1. Scan & Pay -->
        <div class="payment-box">
          <h4>Scan & Pay</h4>
          <div class="qr-container">
            <img src="{{ $qrCode }}" alt="UPI QR Code">
          </div>
          <p style="font-size: 0.85rem; color: #6c757d; font-weight: 600;">Scan using any UPI App</p>
        </div>

        <!-- 2. Pay via UPI Apps -->
        <div class="payment-box">
          <h4>Pay via UPI Apps</h4>
          @php
            $upiUrl = "upi://pay?pa=" . $upiId . "&pn=Kerala%20State%20Lotteries&am=" . $booking->total_price . "&cu=INR";
          @endphp
          <div class="upi-btn-stack">
            <a href="{{ $upiUrl }}" class="upi-btn upi-gpay">Google Pay</a>
            <a href="{{ $upiUrl }}" class="upi-btn upi-phonepe">PhonePe</a>
            <a href="{{ $upiUrl }}" class="upi-btn upi-paytm">Paytm</a>
            <a href="{{ $upiUrl }}" class="upi-btn upi-other">Other UPI Apps</a>
          </div>
        </div>

        <!-- 3. Bank Transfer -->
        <div class="payment-box">
          <h4>Bank Transfer</h4>
          <div class="bank-details">
            <p><strong>Bank:</strong> State Bank of India</p>
            <p><strong>Account:</strong> 53845623856</p>
            <p><strong>IFSC:</strong> SBIN0030466</p>
          </div>
        </div>
      </div>

      <!-- Errors block -->
      @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px; margin-bottom: 1.5rem; text-align: left;">
          <ul style="margin-left: 1rem; margin-bottom: 0;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Payment Submission (Screenshot Upload) -->
      <form action="{{ route('book-ticket.pay-success') }}" method="POST" enctype="multipart/form-data" style="text-align: center;">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

        <div class="screenshot-section">
          <div class="screenshot-title">Upload Payment Screenshot</div>
          <p class="screenshot-sub">Please upload your payment receipt or success screenshot. Max file size: 10MB.</p>
          
          <input type="file" name="screenshot" id="screenshot" class="form-control" accept="image/*" required style="max-width: 400px; margin: 0 auto;">
        </div>

        <button type="submit" class="btn-submit-proof">Submit Payment Proof</button>
      </form>

    </div>
  </div>
@endsection
