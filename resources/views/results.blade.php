@extends('layouts.app')

@php
  $setting = \App\Models\WebsiteSetting::first();
  $qrCode = $setting && $setting->qr_code ? asset($setting->qr_code) : asset('images/qr_code.jpeg');
  $upiId = $setting ? $setting->upi_id : '9369873638-t50f@ybl';
@endphp

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
    <div class="container" style="max-width: 600px; margin: 0 auto;">

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
  <!-- jQuery, jQuery Confirm & Canvas Confetti -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
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
                  if (response.status === 'won') {
                      // 🎉 Winner! Fire confetti and redirect
                      confetti({
                          particleCount: 150,
                          spread: 80,
                          origin: { y: 0.6 }
                      });
                      setTimeout(function() {
                          window.location.href = "{{ route('results.winner') }}?ticket=" + encodeURIComponent(ticket) + "&mobile=" + encodeURIComponent(mobile);
                      }, 1200);

                  } else if (response.status === 'no_win') {
                      // ✅ Ticket & mobile match, but not a winner this time
                      $.confirm({
                          title: '🎟 Draw Result',
                          content: `
                              <div style="text-align: center; padding: 10px 0;">
                                  <div style="font-size: 3rem; margin-bottom: 10px;">😔</div>
                                  <h3 style="color: #ffc107; margin-bottom: 12px;">Better Luck Next Time!</h3>
                                  <p style="font-size: 1rem; color: #ccc;">Your ticket <strong style="color: #f8ab37;">${ticket}</strong> did not win any prize in the latest draw.</p>
                                  <p style="font-size: 0.88rem; color: #888; margin-top: 12px;">Every ticket is a new chance. Keep playing!</p>
                              </div>
                          `,
                          theme: 'dark',
                          buttons: {
                              play: {
                                  text: '🎟 Buy More Tickets',
                                  btnClass: 'btn-success',
                                  action: function() {
                                      window.location.href = "{{ route('buy-tickets') }}";
                                  }
                              },
                              close: { text: 'Close' }
                          }
                      });

                  } else {
                      // ❌ Ticket + mobile don't match any purchase record
                      $.confirm({
                          title: '❌ Not Found',
                          content: `
                              <div style="text-align: center; padding: 10px 0;">
                                  <div style="font-size: 3rem; margin-bottom: 10px;">🔍</div>
                                  <h3 style="color: #e74c3c; margin-bottom: 12px;">Details Not Matched</h3>
                                  <p style="font-size: 1rem; color: #ccc;">Please check your <strong style="color: #f8ab37;">Ticket Number</strong> and <strong style="color: #f8ab37;">Mobile Number</strong> and try again.</p>
                                  <p style="font-size: 0.88rem; color: #888; margin-top: 12px;">Make sure you enter the exact ticket number and the mobile number used during purchase.</p>
                              </div>
                          `,
                          theme: 'dark',
                          buttons: {
                              retry: {
                                  text: '🔄 Try Again',
                                  btnClass: 'btn-warning',
                                  action: function() { /* just closes */ }
                              },
                              buy: {
                                  text: '🎟 Buy Tickets',
                                  btnClass: 'btn-success',
                                  action: function() {
                                      window.location.href = "{{ route('buy-tickets') }}";
                                  }
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
