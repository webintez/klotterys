@extends('layouts.admin')

@section('title', 'Manage Draw Results')

@section('content')
<div class="content-header">
  <div class="content-title">
    <h1>Manage Draw Results</h1>
    <p>View, search, create, update, or remove winning lottery draw numbers.</p>
  </div>
</div>

<div class="result-split-layout">
  <!-- Left Panel: Results List -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Draw Results List</h2>
      <!-- Search inside header -->
      <form action="{{ route('admin.results.index') }}" method="GET" style="display: flex; gap: 0.5rem; max-width: 300px;">
        <input type="text" name="search" class="form-control" placeholder="Search name/draw..." value="{{ request('search') }}" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
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
              <th>Winning Number</th>
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
                  <span class="ticket-tag vl" style="font-size: 1rem; padding: 0.4rem 0.8rem; letter-spacing: 0.5px; border-radius: 6px;">
                    {{ $result->winning_number }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button type="button" class="btn-action edit-btn" 
                            data-id="{{ $result->id }}" 
                            data-date="{{ $result->draw_date }}" 
                            data-name="{{ $result->lottery_name }}" 
                            data-number="{{ $result->draw_number }}" 
                            data-winning="{{ $result->winning_number }}">Edit</button>
                    
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
                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">No draw results found.</td>
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
          <label class="form-label" for="winning_number">Winning Number</label>
          <input type="text" name="winning_number" id="winning_number" class="form-control" placeholder="e.g. VL324506" required value="{{ old('winning_number') }}">
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

    const defaultAction = "{{ route('admin.results.store') }}";

    // Edit button click event
    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const date = this.getAttribute('data-date');
        const name = this.getAttribute('data-name');
        const number = this.getAttribute('data-number');
        const winning = this.getAttribute('data-winning');

        // Populate fields
        drawDateInput.value = date;
        lotteryNameInput.value = name;
        drawNumberInput.value = number;
        winningNumberInput.value = winning;

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
