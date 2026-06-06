@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="content-header">
  <div class="content-title">
    <h1>Manage Bookings</h1>
    <p>Search, filter, edit statuses, and manage user ticket purchases.</p>
  </div>
</div>

<!-- Search & Filter Panel -->
<div class="search-filter-panel">
  <form action="{{ route('admin.bookings.index') }}" method="GET">
    <div class="filter-group" style="flex: 2;">
      <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Search Query</label>
      <input type="text" name="search" class="form-control" placeholder="Search by name, mobile, tickets, tracking..." value="{{ request('search') }}">
    </div>

    <div class="filter-group">
      <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Booking Status</label>
      <select name="status" class="form-control">
        <option value="">-- All Statuses --</option>
        <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
      </select>
    </div>

    <div class="filter-group action">
      <button type="submit" class="btn-admin" style="padding: 0.7rem 1.5rem;">Filter</button>
      @if (request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.bookings.index') }}" class="btn-admin-secondary" style="padding: 0.7rem 1.5rem; text-decoration: none; margin-left: 0.5rem; display: inline-block; white-space: nowrap;">Reset</a>
      @endif
    </div>
  </form>
</div>

<!-- Bookings Table Card -->
<div class="admin-card">
  <div class="admin-card-body" style="padding: 0;">
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Mobile</th>
            <th>Location</th>
            <th>Tickets</th>
            <th>Total Price</th>
            <th>Tracking #</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($bookings as $booking)
            <tr>
              <td>#{{ $booking->id }}</td>
              <td style="font-weight: 600;">{{ $booking->fullname }}</td>
              <td>{{ $booking->mobile }}</td>
              <td style="font-size: 0.85rem; color: var(--text-muted);">
                {{ $booking->state }} ({{ $booking->pincode }})
              </td>
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
              <td style="font-family: monospace; font-weight: bold; color: var(--text-muted);">
                {{ $booking->tracking_number ?: 'N/A' }}
              </td>
              <td>
                <span class="badge badge-{{ $booking->status }}">
                  {{ str_replace('_', ' ', $booking->status) }}
                </span>
              </td>
              <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
              <td>
                <div class="action-buttons">
                  <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn-action">Edit</a>
                  <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action" style="color: var(--danger); border-color: rgba(231, 29, 54, 0.2); background: transparent;">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 3rem;">No bookings found matching the filters.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Pagination -->
@if ($bookings->hasPages())
  <div class="pagination-wrapper">
    {{ $bookings->links() }}
  </div>
@endif

@endsection
