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
  <!-- Results Check Form (Full Screen Gradient Background) -->
  <section class="section" style="background: linear-gradient(135deg, #02022b 0%, #00bfff 100%); min-height: calc(100vh - 75px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; margin: 0; width: 100%;">
    <div class="container" style="max-width: 600px; width: 100%;">

      <!-- Check Your Ticket Card -->
      <div class="card" style="text-align: left; background: #ffffff; color: #333333; border: 1px solid #dee2e6; border-radius: 12px; padding: 5px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); width: 100%;">
        <img src="{{ asset('images/kerala-lottery-result.jpg') }}" alt="Kerala Lottery Result" style="width: 100%; border-radius: 8px 8px 0 0; display: block;">
        
        <div style="padding: 1.5rem 1rem 1rem 1rem;">
          <h2 style="color: #007bff; text-align: center; font-weight: 800; font-size: 1.5rem; line-height: 1.3; margin-top: 0.5rem; margin-bottom: 1rem; text-transform: uppercase; font-family: 'Outfit', sans-serif;">
              CHECK YOUR LOTTERY<br>RESULT
          </h2>
          <div style="height: 5px; background: #007bff; width: 100%; margin-bottom: 2rem; border-radius: 2px;"></div>
          
          <form id="checkTicketForm">
            @csrf
            <div style="margin-bottom: 1.25rem;">
              <input type="text" id="ticket_number" placeholder="Enter Ticket Number" required style="width: 100%; border: 1px solid #dee2e6; outline: none; padding: 0.85rem 1rem; font-size: 1rem; background: #fff; color: #333; border-radius: 8px; font-family: sans-serif; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.5rem;">
              <input type="tel" id="mobile_number" placeholder="Enter Mobile Number" required style="width: 100%; border: 1px solid #dee2e6; outline: none; padding: 0.85rem 1rem; font-size: 1rem; background: #fff; color: #333; border-radius: 8px; font-family: sans-serif; box-sizing: border-box;">
            </div>

            <div style="text-align: center; margin-top: 1.5rem;">
              <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.25rem; font-weight: 700; background: linear-gradient(to right, #050549, #00bfff); color: #ffffff; border: none; border-radius: 8px; cursor: pointer; text-transform: none; box-shadow: 0 4px 15px rgba(0, 191, 255, 0.25);">Check Result</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Result Status Card -->
      @if (session('error_status'))
      <div id="resultStatusCard" class="card" style="margin-top: 1.5rem; text-align: center; padding: 2rem; background: #ffffff; color: #333333; border: 1px solid #dee2e6; border-radius: 12px; width: 100%; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
          <div id="resultStatusIcon" style="font-size: 3rem; margin-bottom: 1rem;">
              @if (session('error_status') === 'mismatch')
                  ❌
              @else
                  🍀
              @endif
          </div>
          <h3 id="resultStatusTitle" style="color: #007bff; margin-bottom: 1rem; font-size: 1.6rem; font-weight: 800; text-transform: uppercase;">
              @if (session('error_status') === 'mismatch')
                  Check Details
              @else
                  Better Luck Next Time!
              @endif
          </h3>
          <p id="resultStatusText" style="color: #6c757d; font-size: 1.05rem; line-height: 1.6;">
              @if (session('error_status') === 'mismatch')
                  please check the ticket number and the mobile number
              @else
                  Ticket <span style="color: var(--primary-color); font-weight: bold;">{{ session('error_ticket') }}</span> did not win any prize in the latest draw.<br>
                  <span style="font-size: 0.9rem; color: #6c757d; display: block; margin-top: 10px;">Don't lose hope. Every ticket brings a new chance. Play again today!</span>
              @endif
          </p>
          <div id="resultStatusButtons" style="margin-top: 1.5rem;">
              @if (session('error_status') === 'mismatch')
                  <button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; padding: 0.75rem; font-weight: 700; background: #343a40; color: #fff; border: none; border-radius: 8px;">Try Again</button>
              @else
                  <a href="{{ route('buy-tickets') }}" class="btn" style="display: block; width: 100%; margin-bottom: 8px; text-decoration: none; text-align: center; line-height: 2.2; background: linear-gradient(45deg, #28a745, #218838); border: none; color: #fff; font-weight: 700; border-radius: 8px;">Buy More Tickets</a>
                  <button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; margin-top: 8px; background: #6c757d; color: #fff; border: none; border-radius: 8px; padding: 0.75rem; font-weight: 700;">Close</button>
              @endif
          </div>
      </div>
      @else
      <div id="resultStatusCard" class="card" style="display: none; margin-top: 1.5rem; text-align: center; padding: 2rem; background: #ffffff; color: #333333; border: 1px solid #dee2e6; border-radius: 12px; width: 100%; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
          <div id="resultStatusIcon" style="font-size: 3rem; margin-bottom: 1rem;"></div>
          <h3 id="resultStatusTitle" style="color: #007bff; margin-bottom: 1rem; font-size: 1.6rem; font-weight: 800; text-transform: uppercase;"></h3>
          <p id="resultStatusText" style="color: #6c757d; font-size: 1.05rem; line-height: 1.6;"></p>
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
              $("#resultStatusButtons").html(`<button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; padding: 0.75rem; font-weight: 700; background: #343a40; color: #fff; border: none; border-radius: 8px;">Try Again</button>`);
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
                          buttons = `<button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; padding: 0.75rem; font-weight: 700; background: #343a40; color: #fff; border: none; border-radius: 8px;">Try Again</button>`;
                      } else {
                          icon = "🍀";
                          title = "Better Luck Next Time!";
                          message = `Ticket <span style="color: var(--primary-color); font-weight: bold;">${ticket}</span> did not win any prize in the latest draw.<br><span style="font-size: 0.9rem; color: #6c757d; display: block; margin-top: 10px;">Don't lose hope. Every ticket brings a new chance. Play again today!</span>`;
                          buttons = `<a href="{{ route('buy-tickets') }}" class="btn" style="display: block; width: 100%; margin-bottom: 8px; text-decoration: none; text-align: center; line-height: 2.2; background: linear-gradient(45deg, #28a745, #218838); border: none; color: #fff; font-weight: 700; border-radius: 8px;">Buy More Tickets</a>
                                     <button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; margin-top: 8px; background: #6c757d; color: #fff; border: none; border-radius: 8px; padding: 0.75rem; font-weight: 700;">Close</button>`;
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
                  $("#resultStatusButtons").html(`<button class="btn" onclick="$('#resultStatusCard').hide();" style="width: 100%; padding: 0.75rem; font-weight: 700; background: #343a40; color: #fff; border: none; border-radius: 8px;">Try Again</button>`);
                  $("#resultStatusCard").slideDown();
              }
          });
      });
  });
  </script>
@endsection
