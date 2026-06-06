@extends('layouts.admin')

@section('title', 'Edit Booking #' . $booking->id)

@section('content')
<div class="content-header">
  <div class="content-title">
    <h1>Edit Booking #{{ $booking->id }}</h1>
    <p>View customer details, bought tickets, and update status/tracking code.</p>
  </div>
  <a href="{{ route('admin.bookings.index') }}" class="btn-admin-secondary" style="text-decoration: none;">← Back to Bookings</a>
</div>

<div class="dashboard-grid">
  <!-- Booking Info Card -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Booking Information</h2>
    </div>
    <div class="admin-card-body">
      <div class="booking-details-grid">
        <div class="detail-item">
          <span class="detail-label">Customer Name</span>
          <div class="detail-value">{{ $booking->fullname }}</div>
        </div>

        <div class="detail-item">
          <span class="detail-label">Mobile Number</span>
          <div class="detail-value">{{ $booking->mobile }}</div>
        </div>

        <div class="detail-item">
          <span class="detail-label">State</span>
          <div class="detail-value">{{ $booking->state }}</div>
        </div>

        <div class="detail-item">
          <span class="detail-label">Pincode</span>
          <div class="detail-value">{{ $booking->pincode }}</div>
        </div>

        <div class="detail-item" style="grid-column: 1 / -1;">
          <span class="detail-label">Registered Tickets ({{ count(array_filter(explode(',', $booking->tickets))) }} total)</span>
          <div class="tickets-container">
            @php
              $tickets = array_filter(explode(',', $booking->tickets));
            @endphp
            @foreach ($tickets as $ticket)
              @php
                $prefix = strtolower(substr($ticket, 0, 2));
              @endphp
              <span class="ticket-tag {{ $prefix === 'vl' ? 'vl' : ($prefix === 'sl' ? 'sl' : '') }}" style="font-size: 0.95rem; padding: 0.4rem 0.8rem;">{{ $ticket }}</span>
            @endforeach
          </div>
        </div>

        <div class="detail-item">
          <span class="detail-label">Total Price Paid</span>
          <div class="detail-value" style="color: var(--primary); font-size: 1.5rem;">₹{{ number_format($booking->total_price) }}</div>
        </div>

        <div class="detail-item">
          <span class="detail-label">Order Date</span>
          <div class="detail-value">{{ $booking->created_at->format('F d, Y h:i A') }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Action Form Card -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Update Booking Status</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label" for="status">Order Status</label>
          <select name="status" id="status" class="form-control" required>
            <option value="pending_payment" {{ $booking->status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
            <option value="paid" {{ $booking->status === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="tracking_number">Tracking Number (Courier/SpeedPost)</label>
          <input type="text" name="tracking_number" id="tracking_number" class="form-control" placeholder="e.g. IN1234567890" value="{{ old('tracking_number', $booking->tracking_number) }}">
          <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">This tracking number will be visible to the customer when they search via order tracking.</span>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
          <button type="submit" class="btn-admin" style="flex: 1;">Save Changes</button>
          <a href="{{ route('admin.bookings.index') }}" class="btn-admin-secondary" style="flex: 1; text-align: center; text-decoration: none; display: flex; justify-content: center; align-items: center;">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
