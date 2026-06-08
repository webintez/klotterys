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

      <!-- Result Status Card -->
      @if (session('error_status'))
      <div id="resultStatusCard" class="card" style="margin-top: 1.5rem; text-align: center; padding: 2rem; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 15px; width: 100%;">
          <div id="resultStatusIcon" style="font-size: 3rem; margin-bottom: 1rem;">
              @if (session('error_status') === 'mismatch')
                  ❌
              @else
                  🍀
              @endif
          </div>
          <h3 id="resultStatusTitle" style="color: var(--secondary-color); margin-bottom: 1rem; font-size: 1.6rem; font-weight: 800; text-transform: uppercase;">
              @if (session('error_status') === 'mismatch')
                  Check Details
              @else
                  Better Luck Next Time!
              @endif
          </h3>
          <p id="resultStatusText" style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6;">
              @if (session('error_status') === 'mismatch')
                  please check the ticket number and the mobile number
              @else
                  Ticket <span style="color: var(--primary-color); font-weight: bold;">{{ session('error_ticket') }}</span> did not win any prize in the latest draw.<br>
                  <span style="font-size: 0.9rem; color: var(--text-muted); display: block; margin-top: 10px;">Don't lose hope. Every ticket brings a new chance. Play again today!</span>
              @endif
          </p>
          <div id="resultStatusButtons" style="margin-top: 1.5rem;">
              @if (session('error_status') === 'mismatch')
                  <button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%;">Try Again</button>
              @else
                  <a href="{{ route('buy-tickets') }}" class="btn" style="display: block; width: 100%; margin-bottom: 8px; text-decoration: none; text-align: center; line-height: 2.2; background: linear-gradient(45deg, #28a745, #218838); border: none;">Buy More Tickets</a>
                  <button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; margin-top: 8px; background: rgba(255, 255, 255, 0.1); border: 1px solid var(--border-color); color: #fff;">Close</button>
              @endif
          </div>
      </div>
      @else
      <div id="resultStatusCard" class="card" style="display: none; margin-top: 1.5rem; text-align: center; padding: 2rem; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 15px; width: 100%;">
          <div id="resultStatusIcon" style="font-size: 3rem; margin-bottom: 1rem;"></div>
          <h3 id="resultStatusTitle" style="color: var(--secondary-color); margin-bottom: 1rem; font-size: 1.6rem; font-weight: 800; text-transform: uppercase;"></h3>
          <p id="resultStatusText" style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6;"></p>
          <div id="resultStatusButtons" style="margin-top: 1.5rem;"></div>
      </div>
      @endif

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
              $("#resultStatusIcon").html("❌");
              $("#resultStatusTitle").html("Check Details");
              $("#resultStatusText").html("please check the ticket number and the mobile number");
              $("#resultStatusButtons").html(`<button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%;">Try Again</button>`);
              $("#resultStatusCard").slideDown();
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
              beforeSend: function() {
                  $("#resultStatusCard").hide();
              },
              success: function(response) {
                  if (response.won) {
                      // Trigger canvas-confetti firecrackers!
                      confetti({
                          particleCount: 150,
                          spread: 80,
                          origin: { y: 0.6 }
                      });

                      setTimeout(function() {
                          window.location.href = "{{ route('results.winner') }}?ticket=" + encodeURIComponent(ticket) + "&mobile=" + encodeURIComponent(mobile);
                      }, 1200);
                  } else {
                      let icon = "";
                      let title = "";
                      let message = "";
                      let buttons = "";

                      if (response.status === 'mismatch') {
                          icon = "❌";
                          title = "Check Details";
                          message = "please check the ticket number and the mobile number";
                          buttons = `<button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%;">Try Again</button>`;
                      } else {
                          icon = "🍀";
                          title = "Better Luck Next Time!";
                          message = `Ticket <span style="color: var(--primary-color); font-weight: bold;">${ticket}</span> did not win any prize in the latest draw.<br><span style="font-size: 0.9rem; color: var(--text-muted); display: block; margin-top: 10px;">Don't lose hope. Every ticket brings a new chance. Play again today!</span>`;
                          buttons = `<a href="{{ route('buy-tickets') }}" class="btn" style="display: block; width: 100%; margin-bottom: 8px; text-decoration: none; text-align: center; line-height: 2.2; background: linear-gradient(45deg, #28a745, #218838); border: none;">Buy More Tickets</a>
                                     <button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; margin-top: 8px; background: rgba(255, 255, 255, 0.1); border: 1px solid var(--border-color); color: #fff;">Close</button>`;
                      }

                      $("#resultStatusIcon").html(icon);
                      $("#resultStatusTitle").html(title);
                      $("#resultStatusText").html(message);
                      $("#resultStatusButtons").html(buttons);
                      $("#resultStatusCard").slideDown();

                      $('html, body').animate({
                          scrollTop: $("#resultStatusCard").offset().top - 100
                      }, 500);
                  }
              },
              error: function() {
                  $("#resultStatusIcon").html("❌");
                  $("#resultStatusTitle").html("Check Details");
                  $("#resultStatusText").html("please check the ticket number and the mobile number");
                  $("#resultStatusButtons").html(`<button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%;">Try Again</button>`);
                  $("#resultStatusCard").slideDown();
              }
          });
      });
  });
  </script>
@endsection
