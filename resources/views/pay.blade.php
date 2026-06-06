@extends('layouts.app')

@section('title', 'Kerala State Lotteries | UPI Payment')

@section('styles')
  <style>
    body {
      background: linear-gradient(135deg, #00ecbc 0%, #007adf 100%) !important;
      min-height: 100vh;
    }
    .pay-card {
      background: rgba(255, 255, 255, 0.95);
      border: none;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
      max-width: 550px;
      width: 100%;
      padding: 2.5rem;
      margin: 50px auto;
      text-align: center;
    }
    .qr-mock {
      width: 200px;
      height: 200px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 12px;
      margin: 20px auto;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .qr-mock img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .amount-display {
      font-size: 2.5rem;
      font-weight: 800;
      color: #28a745;
      margin: 15px 0;
    }
    .ticket-badge {
      display: inline-block;
      background: #17a2b8;
      color: #fff;
      padding: 5px 12px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 0.85rem;
      margin: 3px;
    }
  </style>
@endsection

@section('content')
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="pay-card">
      <h3 style="font-weight: 800; color: #007adf; text-transform: uppercase;">Scan & Pay UPI</h3>
      <p style="color: var(--text-muted); font-size: 0.95rem;">
        Scan the QR code below using any UPI app (GPay, PhonePe, Paytm) to make the payment.
      </p>

      <div class="qr-mock">
        <!-- Google QR code API for UPI payments mock -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=keralastatelotteries@upi&pn=Kerala%20State%20Lotteries&am={{ $booking->total_price }}&cu=INR" alt="UPI QR Code">
      </div>

      <p class="text-dark font-weight-bold mb-0">Total Amount to Pay:</p>
      <div class="amount-display">₹{{ number_format($booking->total_price) }}</div>

      <div style="background: rgba(0, 122, 223, 0.05); padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid rgba(0, 122, 223, 0.1);">
        <p class="text-dark mb-1 text-left"><strong>Name:</strong> {{ $booking->fullname }}</p>
        <p class="text-dark mb-1 text-left"><strong>Mobile:</strong> {{ $booking->mobile }}</p>
        <p class="text-dark mb-0 text-left">
          <strong>Tickets:</strong> 
          @foreach(explode(',', $booking->tickets) as $t)
            <span class="ticket-badge">{{ $t }}</span>
          @endforeach
        </p>
      </div>

      <!-- Payment Simulation Form -->
      <form action="{{ route('book-ticket.pay-success') }}" method="POST">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <button type="submit" class="btn btn-success btn-block" style="border-radius: 50px; font-weight: 700; text-transform: uppercase; padding: 12px;">
          Simulate Payment Success
        </button>
      </form>
    </div>
  </div>
@endsection
