@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="content-header">
  <div class="content-title">
    <h1>Dashboard</h1>
    <p>Overview of booking statistics, revenue, and recent activities.</p>
  </div>
</div>

<!-- Metrics Cards -->
<div class="metrics-grid">
  <div class="metric-card primary">
    <span class="metric-title">Total Revenue</span>
    <span class="metric-value">₹{{ number_format($totalRevenue) }}</span>
    <span class="metric-sub">From all successful purchases</span>
  </div>

  <div class="metric-card success">
    <span class="metric-title">Tickets Sold</span>
    <span class="metric-value">{{ number_format($totalTicketsSold) }}</span>
    <span class="metric-sub">Active registered tickets</span>
  </div>

  <div class="metric-card info">
    <span class="metric-title">Paid Bookings</span>
    <span class="metric-value">{{ number_format($paidCount) }}</span>
    <span class="metric-sub">Bookings successfully completed</span>
  </div>

  <div class="metric-card warning">
    <span class="metric-title">Pending Bookings</span>
    <span class="metric-value">{{ number_format($pendingCount) }}</span>
    <span class="metric-sub">Awaiting payment verification</span>
  </div>
</div>

<!-- Content Grid -->
<div class="dashboard-grid">
  <!-- Recent Bookings -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Recent Bookings</h2>
      <a href="{{ route('admin.bookings.index') }}" class="btn-admin-secondary" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">View All</a>
    </div>
    <div class="admin-card-body" style="padding: 0;">
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Mobile</th>
              <th>Tickets</th>
              <th>Price</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentBookings as $booking)
              <tr>
                <td>#{{ $booking->id }}</td>
                <td style="font-weight: 600;">{{ $booking->fullname }}</td>
                <td>{{ $booking->mobile }}</td>
                <td>
                  <div class="tickets-container" style="margin-top: 0;">
                    @php
                      $tickets = array_filter(explode(',', $booking->tickets));
                    @endphp
                    @foreach ($tickets as $ticket)
                      @php
                        $prefix = strtolower(substr($ticket, 0, 2));
                      @endphp
                      <span class="ticket-tag {{ $prefix === 'vl' ? 'vl' : ($prefix === 'sl' ? 'sl' : '') }}">{{ $ticket }}</span>
                    @endforeach
                  </div>
                </td>
                <td style="font-weight: 600;">₹{{ number_format($booking->total_price) }}</td>
                <td>
                  <span class="badge badge-{{ $booking->status }}">
                    {{ str_replace('_', ' ', $booking->status) }}
                  </span>
                </td>
                <td>{{ $booking->created_at->format('M d, Y h:i A') }}</td>
                <td>
                  <div class="action-buttons">
                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn-action">Edit</a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2rem;">No bookings found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Quick Stats / Actions -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Quick Stats</h2>
    </div>
    <div class="admin-card-body">
      <div class="detail-item" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
        <span class="detail-label">Total Bookings (All time)</span>
        <div class="detail-value">{{ $totalBookings }}</div>
      </div>
      <div class="detail-item" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
        <span class="detail-label">Total Draw Results Listed</span>
        <div class="detail-value">{{ $totalDraws }}</div>
      </div>
      <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
        <a href="{{ route('admin.results.index') }}" class="btn-admin" style="text-align: center; text-decoration: none;">Add Draw Result</a>
        <a href="{{ route('admin.bookings.index') }}" class="btn-admin-secondary" style="text-align: center; text-decoration: none;">Manage All Bookings</a>
      </div>
    </div>
  </div>
</div>
@endsection
