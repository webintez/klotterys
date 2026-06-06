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
  <form action="{{ route('admin.bookings.index') }}" method="GET" id="date-filter-form">
    <div class="filter-group" style="flex: 2; min-width: 250px;">
      <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Search Query</label>
      <input type="text" name="search" class="form-control" placeholder="Search by name, mobile, tickets, tracking..." value="{{ request('search') }}">
    </div>

    <div class="filter-group" style="min-width: 150px;">
      <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Booking Status</label>
      <select name="status" class="form-control">
        <option value="">-- All Statuses --</option>
        <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
      </select>
    </div>

    <div class="filter-group" style="min-width: 180px;">
      <label class="form-label" style="font-size: 0.75rem; margin-bottom: 0.25rem;">Date Filter</label>
      <select name="date_filter" id="date_filter" class="form-control" onchange="toggleCustomDates()">
        <option value="today" {{ request('date_filter', 'today') == 'today' ? 'selected' : '' }}>Today</option>
        <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Previous Day (Yesterday)</option>
        <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
        <option value="last_week" {{ request('date_filter') == 'last_week' ? 'selected' : '' }}>Last Week</option>
        <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
        <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
        <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
        <option value="last_year" {{ request('date_filter') == 'last_year' ? 'selected' : '' }}>Last Year</option>
        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
      </select>
    </div>

    <div id="custom-date-inputs" style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }}; gap: 0.4rem; align-items: center; margin-top: 1rem; flex-basis: 100%;">
      <div style="display: flex; gap: 0.5rem; align-items: center;">
        <label class="form-label" style="margin-bottom: 0; white-space: nowrap;">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control" style="width: auto; padding: 0.5rem;" value="{{ request('start_date') }}">
        <label class="form-label" style="margin-bottom: 0; white-space: nowrap; margin-left: 0.5rem;">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control" style="width: auto; padding: 0.5rem;" value="{{ request('end_date') }}">
      </div>
    </div>

    <div class="filter-group action" style="align-self: flex-end;">
      <button type="submit" class="btn-admin" style="padding: 0.7rem 1.5rem;">Filter</button>
      @if (request()->hasAny(['search', 'status', 'date_filter']))
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

@section('scripts')
<script>
  function toggleCustomDates() {
    const filter = document.getElementById('date_filter').value;
    const customInputs = document.getElementById('custom-date-inputs');
    if (filter === 'custom') {
        customInputs.style.display = 'flex';
        document.getElementById('start_date').required = true;
        document.getElementById('end_date').required = true;
    } else {
        customInputs.style.display = 'none';
        document.getElementById('start_date').required = false;
        document.getElementById('end_date').required = false;
        document.getElementById('date-filter-form').submit();
    }
  }
</script>
@endsection
