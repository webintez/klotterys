@extends('layouts.admin')

@section('title', 'Manage Draw Results')

@section('styles')
<style>
  .price-tabs {
    display: flex;
    background: #e9ecef;
    padding: 0.35rem;
    border-radius: 8px;
    margin: 1rem 1.5rem 1.5rem 1.5rem;
    border: 1px solid #dee2e6;
  }
  .price-tab-btn {
    flex: 1;
    padding: 0.6rem 1rem;
    border: none;
    background: transparent;
    color: #495057;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    font-family: 'Outfit', sans-serif;
  }
  .price-tab-btn.active {
    background: var(--primary);
    color: #ffffff;
    box-shadow: 0 4px 6px rgba(255, 87, 34, 0.15);
  }
  .price-tab-btn:hover:not(.active) {
    background: rgba(0, 0, 0, 0.04);
    color: #212529;
  }
  .tab-content {
    display: none;
    animation: fadeInTab 0.3s ease;
  }
  .tab-content.active {
    display: block;
  }
  @keyframes fadeInTab {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
@endsection

@section('content')
<div class="content-header">
  <div class="content-title">
    <h1>Manage Draw Results</h1>
    <p>Declare winning numbers for today's bookings or manage historical results.</p>
  </div>
</div>

<!-- Today's Bookings Section -->
<div class="admin-card" style="margin-bottom: 2rem;">
  <div class="admin-card-header" style="border-bottom: none; padding-bottom: 0.5rem;">
    <h2>Today's Bookings & Ticket Purchases ({{ date('Y-m-d') }})</h2>
    <span class="badge badge-paid">{{ count($todayBookings) }} Bookings Today</span>
  </div>

  <!-- Tabs based on Ticket Price -->
  <div class="price-tabs">
    <button type="button" class="price-tab-btn active" onclick="switchTab('price-500')" id="tab-price-500">
      ₹500 Tickets (Win Win) ({{ count($bookings500) }})
    </button>
    <button type="button" class="price-tab-btn" onclick="switchTab('price-149')" id="tab-price-149">
      ₹149 Tickets (Sthree Sakthi) ({{ count($bookings149) }})
    </button>
    <button type="button" class="price-tab-btn" onclick="switchTab('price-40')" id="tab-price-40">
      ₹40 Tickets (Others) ({{ count($bookings40) }})
    </button>
  </div>

  <div class="admin-card-body" style="padding: 0;">
    <!-- Tab 500 -->
    <div id="content-price-500" class="tab-content active">
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Mobile</th>
              <th>Booking Status</th>
              <th>Bought Tickets & Prize Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($bookings500 as $booking)
              <tr>
                <td style="font-weight: 600;">{{ $booking->fullname }}</td>
                <td>{{ $booking->mobile }}</td>
                <td>
                  <span class="badge badge-{{ $booking->status }}">
                    {{ str_replace('_', ' ', $booking->status) }}
                  </span>
                </td>
                <td>
                  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach ($booking->filtered_tickets as $ticket)
                      @php
                        $prefix = strtolower(substr($ticket, 0, 2));
                      @endphp
                      <div style="display: flex; align-items: center; justify-content: space-between; background: #f8f9fa; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #e9ecef; flex-wrap: wrap; gap: 0.5rem;">
                        <span class="ticket-tag {{ $prefix === 'vl' ? 'vl' : ($prefix === 'sl' ? 'sl' : '') }}" style="font-size: 0.95rem; padding: 0.35rem 0.75rem; margin: 0;">
                          {{ $ticket }}
                        </span>
                        <div class="action-buttons" style="display: flex; gap: 0.5rem; align-items: center;">
                          <select class="form-control prize-select" style="padding: 0.3rem 0.5rem; font-size: 0.85rem; width: 140px; height: auto; display: inline-block;">
                            <option value="1st Prize">1st Prize</option>
                            <option value="2nd Prize">2nd Prize</option>
                            <option value="3rd Prize">3rd Prize</option>
                            <option value="4th Prize">4th Prize</option>
                            <option value="5th Prize">5th Prize</option>
                            <option value="6th Prize">6th Prize</option>
                            <option value="7th Prize">7th Prize</option>
                            <option value="8th Prize">8th Prize</option>
                            <option value="9th Prize">9th Prize</option>
                            <option value="10th Prize">10th Prize</option>
                          </select>
                          <button type="button" class="btn-action select-prize-btn" 
                                  data-ticket="{{ $ticket }}" 
                                  data-prefix="{{ $prefix }}"
                                  style="background: var(--success); color: #fff; border-color: var(--success); font-weight: bold; padding: 0.35rem 0.75rem; font-size: 0.85rem; line-height: 1.2;">
                            Declare
                          </button>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">No ₹500 ticket bookings registered today.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tab 149 -->
    <div id="content-price-149" class="tab-content">
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Mobile</th>
              <th>Booking Status</th>
              <th>Bought Tickets & Prize Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($bookings149 as $booking)
              <tr>
                <td style="font-weight: 600;">{{ $booking->fullname }}</td>
                <td>{{ $booking->mobile }}</td>
                <td>
                  <span class="badge badge-{{ $booking->status }}">
                    {{ str_replace('_', ' ', $booking->status) }}
                  </span>
                </td>
                <td>
                  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach ($booking->filtered_tickets as $ticket)
                      @php
                        $prefix = strtolower(substr($ticket, 0, 2));
                      @endphp
                      <div style="display: flex; align-items: center; justify-content: space-between; background: #f8f9fa; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #e9ecef; flex-wrap: wrap; gap: 0.5rem;">
                        <span class="ticket-tag {{ $prefix === 'vl' ? 'vl' : ($prefix === 'sl' ? 'sl' : '') }}" style="font-size: 0.95rem; padding: 0.35rem 0.75rem; margin: 0;">
                          {{ $ticket }}
                        </span>
                        <div class="action-buttons" style="display: flex; gap: 0.5rem; align-items: center;">
                          <select class="form-control prize-select" style="padding: 0.3rem 0.5rem; font-size: 0.85rem; width: 140px; height: auto; display: inline-block;">
                            <option value="1st Prize">1st Prize</option>
                            <option value="2nd Prize">2nd Prize</option>
                            <option value="3rd Prize">3rd Prize</option>
                            <option value="4th Prize">4th Prize</option>
                            <option value="5th Prize">5th Prize</option>
                            <option value="6th Prize">6th Prize</option>
                            <option value="7th Prize">7th Prize</option>
                            <option value="8th Prize">8th Prize</option>
                            <option value="9th Prize">9th Prize</option>
                            <option value="10th Prize">10th Prize</option>
                          </select>
                          <button type="button" class="btn-action select-prize-btn" 
                                  data-ticket="{{ $ticket }}" 
                                  data-prefix="{{ $prefix }}"
                                  style="background: var(--success); color: #fff; border-color: var(--success); font-weight: bold; padding: 0.35rem 0.75rem; font-size: 0.85rem; line-height: 1.2;">
                            Declare
                          </button>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">No ₹149 ticket bookings registered today.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tab 40 -->
    <div id="content-price-40" class="tab-content">
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Mobile</th>
              <th>Booking Status</th>
              <th>Bought Tickets & Prize Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($bookings40 as $booking)
              <tr>
                <td style="font-weight: 600;">{{ $booking->fullname }}</td>
                <td>{{ $booking->mobile }}</td>
                <td>
                  <span class="badge badge-{{ $booking->status }}">
                    {{ str_replace('_', ' ', $booking->status) }}
                  </span>
                </td>
                <td>
                  <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach ($booking->filtered_tickets as $ticket)
                      @php
                        $prefix = strtolower(substr($ticket, 0, 2));
                      @endphp
                      <div style="display: flex; align-items: center; justify-content: space-between; background: #f8f9fa; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #e9ecef; flex-wrap: wrap; gap: 0.5rem;">
                        <span class="ticket-tag {{ $prefix === 'vl' ? 'vl' : ($prefix === 'sl' ? 'sl' : '') }}" style="font-size: 0.95rem; padding: 0.35rem 0.75rem; margin: 0;">
                          {{ $ticket }}
                        </span>
                        <div class="action-buttons" style="display: flex; gap: 0.5rem; align-items: center;">
                          <select class="form-control prize-select" style="padding: 0.3rem 0.5rem; font-size: 0.85rem; width: 140px; height: auto; display: inline-block;">
                            <option value="1st Prize">1st Prize</option>
                            <option value="2nd Prize">2nd Prize</option>
                            <option value="3rd Prize">3rd Prize</option>
                            <option value="4th Prize">4th Prize</option>
                            <option value="5th Prize">5th Prize</option>
                            <option value="6th Prize">6th Prize</option>
                            <option value="7th Prize">7th Prize</option>
                            <option value="8th Prize">8th Prize</option>
                            <option value="9th Prize">9th Prize</option>
                            <option value="10th Prize">10th Prize</option>
                          </select>
                          <button type="button" class="btn-action select-prize-btn" 
                                  data-ticket="{{ $ticket }}" 
                                  data-prefix="{{ $prefix }}"
                                  style="background: var(--success); color: #fff; border-color: var(--success); font-weight: bold; padding: 0.35rem 0.75rem; font-size: 0.85rem; line-height: 1.2;">
                            Declare
                          </button>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">No ₹40 ticket bookings registered today.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="result-split-layout">
  <!-- Left Panel: Results List -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Draw Results List</h2>
      <!-- Search inside header -->
      <form action="{{ route('admin.results.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 300px;">
        <input type="text" name="search" class="form-control" placeholder="Search name/draw/prize..." value="{{ request('search') }}" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
        <button type="submit" class="btn-admin-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Search</button>
      </form>
    </div>
    
    <div class="admin-card-body" style="padding: 0;">
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Lottery Name</th>
              <th>Draw #</th>
              <th>Prize</th>
              <th>Winning Number</th>
              <th>Winning Amount</th>
              <th>Tax Amount</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($results as $result)
              <tr>
                <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($result->draw_date)->format('Y-m-d') }}</td>
                <td style="font-weight: 600; color: var(--primary);">{{ $result->lottery_name }}</td>
                <td style="font-family: monospace;">{{ $result->draw_number }}</td>
                <td>
                  <span class="badge" style="background-color: {{ $result->prize_category == '1st Prize' ? 'var(--success)' : ($result->prize_category == '2nd Prize' ? 'var(--info)' : 'var(--warning)') }}; color: #ffffff;">
                    {{ $result->prize_category }}
                  </span>
                </td>
                <td>
                  <span class="ticket-tag vl" style="font-size: 1rem; padding: 0.4rem 0.8rem; letter-spacing: 0.5px; border-radius: 6px;">
                    {{ $result->winning_number }}
                  </span>
                </td>
                <td style="font-weight: 600; color: var(--primary);">
                  {{ $result->winning_amount }}
                </td>
                <td style="font-weight: 600; color: var(--danger);">
                  {{ $result->tax_amount ?? '-' }}
                </td>
                <td>
                  <div class="action-buttons">
                    <button type="button" class="btn-action edit-btn" 
                            data-id="{{ $result->id }}" 
                            data-date="{{ $result->draw_date }}" 
                            data-name="{{ $result->lottery_name }}" 
                            data-number="{{ $result->draw_number }}" 
                            data-winning="{{ $result->winning_number }}"
                            data-winning-amount="{{ $result->winning_amount }}"
                            data-tax-amount="{{ $result->tax_amount }}"
                            data-prize="{{ $result->prize_category }}">Edit</button>
                    
                    <form action="{{ route('admin.results.destroy', $result->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this draw result?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-action" style="color: var(--danger); border-color: rgba(231, 29, 54, 0.2); background: transparent;">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem;">No draw results found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right Panel: Form (Add / Edit) -->
  <div class="admin-card" style="height: fit-content;">
    <div class="admin-card-header">
      <h2 id="form-card-title">Add New Draw Result</h2>
    </div>
    <div class="admin-card-body">
      <form id="result-form" action="{{ route('admin.results.store') }}" method="POST">
        @csrf
        <div id="method-container"></div>

        <div class="form-group">
          <label class="form-label" for="draw_date">Draw Date</label>
          <input type="date" name="draw_date" id="draw_date" class="form-control" required value="{{ old('draw_date', date('Y-m-d')) }}">
        </div>

        <div class="form-group">
          <label class="form-label" for="lottery_name">Lottery Name</label>
          <input type="text" name="lottery_name" id="lottery_name" class="form-control" placeholder="e.g. FIFTY-FIFTY" required value="{{ old('lottery_name') }}">
        </div>

        <div class="form-group">
          <label class="form-label" for="draw_number">Draw Number</label>
          <input type="text" name="draw_number" id="draw_number" class="form-control" placeholder="e.g. FF-95" required value="{{ old('draw_number') }}">
        </div>

        <div class="form-group">
          <label class="form-label" for="prize_category">Prize Category</label>
          <select name="prize_category" id="prize_category" class="form-control" required>
            <option value="1st Prize" {{ old('prize_category') == '1st Prize' ? 'selected' : '' }}>1st Prize</option>
            <option value="2nd Prize" {{ old('prize_category') == '2nd Prize' ? 'selected' : '' }}>2nd Prize</option>
            <option value="3rd Prize" {{ old('prize_category') == '3rd Prize' ? 'selected' : '' }}>3rd Prize</option>
            <option value="4th Prize" {{ old('prize_category') == '4th Prize' ? 'selected' : '' }}>4th Prize</option>
            <option value="5th Prize" {{ old('prize_category') == '5th Prize' ? 'selected' : '' }}>5th Prize</option>
            <option value="6th Prize" {{ old('prize_category') == '6th Prize' ? 'selected' : '' }}>6th Prize</option>
            <option value="7th Prize" {{ old('prize_category') == '7th Prize' ? 'selected' : '' }}>7th Prize</option>
            <option value="8th Prize" {{ old('prize_category') == '8th Prize' ? 'selected' : '' }}>8th Prize</option>
            <option value="9th Prize" {{ old('prize_category') == '9th Prize' ? 'selected' : '' }}>9th Prize</option>
            <option value="10th Prize" {{ old('prize_category') == '10th Prize' ? 'selected' : '' }}>10th Prize</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="winning_number">Winning Number</label>
          <input type="text" name="winning_number" id="winning_number" class="form-control" placeholder="e.g. VL324506" required value="{{ old('winning_number') }}">
        </div>

        <div class="form-group">
          <label class="form-label" for="winning_amount">Winning Amount</label>
          <input type="text" name="winning_amount" id="winning_amount" class="form-control" placeholder="e.g. ₹5,000" required value="{{ old('winning_amount') }}">
        </div>

        <div class="form-group">
          <label class="form-label" for="tax_amount">Tax Amount (Optional)</label>
          <input type="text" name="tax_amount" id="tax_amount" class="form-control" placeholder="e.g. 10% or ₹500" value="{{ old('tax_amount') }}">
        </div>

        <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem;">
          <button type="submit" id="submit-btn" class="btn-admin">Save Draw Result</button>
          <button type="button" id="cancel-btn" class="btn-admin-secondary" style="display: none;">Cancel Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Pagination -->
@if ($results->hasPages())
  <div class="pagination-wrapper">
    {{ $results->links() }}
  </div>
@endif

@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');
    const selectPrizeButtons = document.querySelectorAll('.select-prize-btn');
    const form = document.getElementById('result-form');
    const formTitle = document.getElementById('form-card-title');
    const methodContainer = document.getElementById('method-container');
    const submitBtn = document.getElementById('submit-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    // Fields
    const drawDateInput = document.getElementById('draw_date');
    const lotteryNameInput = document.getElementById('lottery_name');
    const drawNumberInput = document.getElementById('draw_number');
    const winningNumberInput = document.getElementById('winning_number');
    const prizeCategorySelect = document.getElementById('prize_category');
    const winningAmountInput = document.getElementById('winning_amount');
    const taxAmountInput = document.getElementById('tax_amount');

    const defaultAction = "{{ route('admin.results.store') }}";

    // Switch tabs function
    window.switchTab = function(tabId) {
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      document.getElementById('content-' + tabId).classList.add('active');

      document.querySelectorAll('.price-tab-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      document.getElementById('tab-' + tabId).classList.add('active');
    };

    // Select Prize from bookings click event
    selectPrizeButtons.forEach(button => {
      button.addEventListener('click', function() {
        const ticket = this.getAttribute('data-ticket');
        const prefix = this.getAttribute('data-prefix');
        // Find adjacent select element
        const selectEl = this.parentElement.querySelector('.prize-select');
        const prize = selectEl.value;

        // Setup form for creating
        cancelBtn.click(); // Reset in case of editing

        // Auto-fill values
        drawDateInput.value = "{{ date('Y-m-d') }}";
        winningNumberInput.value = ticket;
        prizeCategorySelect.value = prize;

        // Auto-fill Winning Amount based on prize selected
        if (prize === '1st Prize') {
          winningAmountInput.value = '₹5,000';
          taxAmountInput.value = '₹500';
        } else if (prize === '2nd Prize') {
          winningAmountInput.value = '₹2,500';
          taxAmountInput.value = '₹250';
        } else if (prize === '3rd Prize') {
          winningAmountInput.value = '₹1,000';
          taxAmountInput.value = '₹100';
        } else {
          winningAmountInput.value = '₹500';
          taxAmountInput.value = '₹50';
        }

        // Auto-fill Lottery Name based on prefix
        if (prefix === 'vl') {
          lotteryNameInput.value = 'Win Win';
        } else if (prefix === 'sl') {
          lotteryNameInput.value = 'Sthree Sakthi';
        } else {
          lotteryNameInput.value = 'Onam Bumper';
        }

        // Auto-fill Draw Number
        const todayStr = "{{ date('Ymd') }}";
        drawNumberInput.value = 'D-' + todayStr;

        // Smooth scroll to form
        form.scrollIntoView({ behavior: 'smooth' });
      });
    });

    // Edit button click event
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const date = this.getAttribute('data-date');
        const name = this.getAttribute('data-name');
        const number = this.getAttribute('data-number');
        const winning = this.getAttribute('data-winning');
        const winningAmount = this.getAttribute('data-winning-amount');
        const taxAmount = this.getAttribute('data-tax-amount');
        const prize = this.getAttribute('data-prize');

        // Populate fields
        drawDateInput.value = date;
        lotteryNameInput.value = name;
        drawNumberInput.value = number;
        winningNumberInput.value = winning;
        prizeCategorySelect.value = prize;
        winningAmountInput.value = winningAmount;
        taxAmountInput.value = taxAmount;

        // Change form setup for editing
        formTitle.textContent = "Edit Draw Result";
        submitBtn.textContent = "Update Draw Result";
        cancelBtn.style.display = "block";

        // Set action route to update
        form.action = `/admin/results/${id}`;
        
        // Add PUT method override
        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Scroll to form on small screen
        form.scrollIntoView({ behavior: 'smooth' });
      });
    });

    // Cancel button click event
    cancelBtn.addEventListener('click', function() {
      // Reset form
      form.reset();
      drawDateInput.value = "{{ date('Y-m-d') }}";

      // Reset form setup for adding
      formTitle.textContent = "Add New Draw Result";
      submitBtn.textContent = "Save Draw Result";
      cancelBtn.style.display = "none";

      // Reset action route
      form.action = defaultAction;
      
      // Remove PUT method
      methodContainer.innerHTML = '';
    });
  });
</script>
@endsection
