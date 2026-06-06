@extends('layouts.app')

@section('title', 'Kerala State Lotteries | Draw Results')

@section('styles')
  <style>
    .results-table-container {
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid var(--border-color);
      border-radius: 15px;
      padding: 2rem;
      overflow-x: auto;
    }
    .search-bar {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .search-bar input, .search-bar select {
      flex: 1;
    }
    .results-layout {
      display: grid;
      grid-template-columns: 1.8fr 1.2fr;
      gap: 2rem;
      align-items: start;
    }
    @media (max-width: 992px) {
      .results-layout {
        grid-template-columns: 1fr;
      }
    }
  </style>
  <!-- jQuery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('content')
  <!-- Page Header -->
  <section class="section" style="padding-bottom: 2rem;">
    <div class="container">
      <h1 class="section-title">Draw Results</h1>
      <p style="text-align: center; color: var(--text-muted); max-width: 600px; margin: 0 auto;">
        Check the latest winning numbers and see if you are our next big winner!
      </p>
    </div>
  </section>

  <!-- Results Section -->
  <section class="section bg-alt" style="padding-top: 3rem;">
    <div class="container results-layout">
      
      <div class="results-table-container">
        <!-- Filters -->
        <form action="{{ route('results') }}" method="GET" class="search-bar">
          <input type="date" name="date" value="{{ request('date') }}" class="form-control">
          <select name="name" class="form-control">
            <option value="">All Lotteries</option>
            <option value="Win Win" {{ request('name') == 'Win Win' ? 'selected' : '' }}>Win Win</option>
            <option value="Sthree Sakthi" {{ request('name') == 'Sthree Sakthi' ? 'selected' : '' }}>Sthree Sakthi</option>
            <option value="Onam Bumper" {{ request('name') == 'Onam Bumper' ? 'selected' : '' }}>Onam Bumper</option>
          </select>
          <button type="submit" class="btn">Filter</button>
        </form>

        <!-- Table -->
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Lottery Name</th>
              <th>Draw No.</th>
              <th>1st Prize Winning Number</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($results as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->draw_date)->format('Y-m-d') }}</td>
                <td style="color: var(--secondary-color); font-weight: bold;">{{ $r->lottery_name }}</td>
                <td>{{ $r->draw_number }}</td>
                <td style="font-size: 1.2rem; font-family: monospace;">{{ $r->winning_number }}</td>
                <td><a href="#" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Full Result</a></td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align: center; color: var(--text-muted);">No results found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Check Your Ticket Card -->
      <div class="card" style="text-align: left; padding: 2rem; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 15px; width: 100%;">
        <h3 style="color: var(--secondary-color); text-align: center; margin-bottom: 1.5rem; font-size: 1.8rem; font-weight: 800; text-transform: uppercase;">Check Your Ticket</h3>
        <p style="color: var(--text-muted); font-size: 0.95rem; text-align: center; margin-bottom: 2rem;">
          Enter your Ticket Number and Mobile Number to instantly check if you have won any prizes in the recent draws.
        </p>
        
        <form id="checkTicketForm">
          @csrf
          <div class="form-group">
            <label for="ticket_number" style="color: var(--text-muted); display: block; margin-bottom: 0.5rem; font-weight: 600;">Ticket Number:</label>
            <input type="text" id="ticket_number" class="form-control" placeholder="e.g. VL-123456" required style="font-size: 1.1rem; text-align: center;">
          </div>
          <div class="form-group" style="margin-top: 1.5rem;">
            <label for="mobile_number" style="color: var(--text-muted); display: block; margin-bottom: 0.5rem; font-weight: 600;">Mobile Number:</label>
            <input type="tel" id="mobile_number" class="form-control" placeholder="e.g. 9876543210" required style="font-size: 1.1rem; text-align: center;">
          </div>
          <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn" style="width: 100%;">Check Winning Status</button>
          </div>
        </form>
      </div>

    </div>
  </section>
@endsection

@section('scripts')
  <!-- jQuery & jQuery Confirm -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script>
  $(document).ready(function() {
      $("#checkTicketForm").submit(function(e) {
          e.preventDefault();
          let ticket = $("#ticket_number").val().trim().toUpperCase();
          let mobile = $("#mobile_number").val().trim();

          if (!ticket || !mobile) {
              $.alert({
                  title: 'Error',
                  content: '❌ Please enter both Ticket Number and Mobile Number.',
                  theme: 'dark'
              });
              return;
          }

          // AJAX request to Laravel route to verify ticket
          $.ajax({
              url: "{{ route('results.check') }}",
              method: "POST",
              data: {
                  _token: "{{ csrf_token() }}",
                  ticket: ticket,
                  mobile: mobile
              },
              success: function(response) {
                  if (response.won) {
                      $.confirm({
                          title: '🎉 CONGRATULATIONS!',
                          content: `
                              <div style="text-align: center; margin-top: 10px;">
                                  <h2 style="color: #28a745; margin-bottom: 15px;">🏆 YOU ARE A WINNER!</h2>
                                  <p style="font-size: 1.1rem;">Ticket <span style="color: var(--secondary-color); font-weight: bold;">${ticket}</span> has won a prize of:</p>
                                  <div style="background: rgba(40,167,69,0.1); border: 2px dashed #28a745; padding: 15px; border-radius: 10px; margin: 15px auto; max-width: 250px;">
                                      <span style="font-size: 2.2rem; color: #28a745; font-weight: 800;">${response.prize}</span>
                                  </div>
                                  <p style="font-size: 0.9rem; color: var(--text-muted);">
                                      Our support team will contact you on <span style="color: var(--secondary-color); font-weight: bold;">${mobile}</span> to verify your ticket and transfer the prize.
                                  </p>
                              </div>
                          `,
                          theme: 'dark',
                          buttons: {
                              claim: {
                                  text: 'Claim Prize Now',
                                  btnClass: 'btn-success',
                                  action: function() {
                                      $.alert({
                                          title: 'Claim Submitted',
                                          content: '✅ Your claim request has been registered. We will call you shortly on ' + mobile,
                                          theme: 'dark'
                                      });
                                  }
                              },
                              close: {
                                  text: 'Close'
                              }
                          }
                      });
                  } else {
                      $.confirm({
                          title: 'Draw Status Checked',
                          content: `
                              <div style="text-align: center; margin-top: 10px;">
                                  <h3 style="color: #ffc107; margin-bottom: 15px;">Better Luck Next Time!</h3>
                                  <p style="font-size: 1.05rem;">Ticket <span style="color: var(--primary-color); font-weight: bold;">${ticket}</span> did not win any prize in the latest draw.</p>
                                  <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 15px;">
                                      Don't lose hope. Every ticket brings a new chance. Play again today!
                                  </p>
                              </div>
                          `,
                          theme: 'dark',
                          buttons: {
                              play: {
                                  text: 'Buy More Tickets',
                                  btnClass: 'btn-success',
                                  action: function() {
                                      window.location.href = "{{ route('buy-tickets') }}";
                                  }
                              },
                              close: {
                                  text: 'Close'
                              }
                          }
                      });
                  }
              },
              error: function() {
                  $.alert({
                      title: 'Error',
                      content: '❌ Something went wrong. Please try again.',
                      theme: 'dark'
                  });
              }
          });
      });
  });
  </script>
@endsection
