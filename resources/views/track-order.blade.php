@extends('layouts.app')

@section('title', 'Kerala State Lotteries | Track Order')

@section('styles')
  <!-- jQuery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('content')
  <!-- Track Order Form (Full Screen Gradient Background) -->
  <section class="section" style="background: linear-gradient(135deg, #02022b 0%, #00bfff 100%); min-height: calc(100vh - 75px); display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; margin: 0; width: 100%;">
    <div class="container" style="max-width: 600px; width: 100%;">
      
      <div class="card" style="text-align: left; background: #ffffff; color: #333333; border: 1px solid #dee2e6; border-radius: 12px; padding: 5px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
        <h2 style="color: #007bff; text-align: center; font-weight: 800; font-size: 1.5rem; line-height: 1.3; margin-top: 0.5rem; margin-bottom: 1rem; text-transform: uppercase; font-family: 'Outfit', sans-serif;">
            Ticket-Booking<br>Status Check
        </h2>
        <div style="height: 5px; background: #007bff; width: 100%; margin-bottom: 2rem; border-radius: 2px;"></div>
        
        <form id="trackForm">
          @csrf
          <div style="display: flex; border: 1.5px solid #333; margin-bottom: 1.25rem; border-radius: 4px; overflow: hidden; background: #fff;">
            <div style="background-color: #343a40; color: #fff; width: 110px; padding: 0.75rem; font-weight: 700; text-align: center; font-size: 0.9rem; border-right: 1.5px solid #333; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: sans-serif;">
              Ticket no.
            </div>
            <input type="text" id="ticket_number" placeholder="Ex. KL854215" required style="flex: 1; border: none; outline: none; padding: 0.75rem 1rem; font-size: 1rem; background: #fff; color: #333; font-family: sans-serif;">
          </div>

          <div style="display: flex; border: 1.5px solid #333; margin-bottom: 1.5rem; border-radius: 4px; overflow: hidden; background: #fff;">
            <div style="background-color: #343a40; color: #fff; width: 110px; padding: 0.75rem; font-weight: 700; text-align: center; font-size: 0.9rem; border-right: 1.5px solid #333; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: sans-serif;">
              Mobile
            </div>
            <input type="tel" id="mobile_number" placeholder="Ex.9874563210" required style="flex: 1; border: none; outline: none; padding: 0.75rem 1rem; font-size: 1rem; background: #fff; color: #333; font-family: sans-serif;">
          </div>

          <div style="text-align: center; margin-top: 1.5rem;">
            <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.25rem; font-weight: 700; background: linear-gradient(to right, #050549, #00bfff); color: #ffffff; border: none; border-radius: 8px; cursor: pointer; text-transform: none; box-shadow: 0 4px 15px rgba(0, 191, 255, 0.25);">Check Now</button>
          </div>
        </form>

        <div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #dee2e6; text-align: center;">
          <p style="color: #6c757d; font-size: 0.9rem; margin: 0;">
            Having trouble tracking your order? Please have your order details ready and <a href="{{ route('contact') }}" style="color: #007bff; font-weight: 600; text-decoration: none;">contact support</a>.
          </p>
        </div>
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
      $("#trackForm").submit(function(e) {
          e.preventDefault();
          let ticket = $("#ticket_number").val().trim();
          let mobile = $("#mobile_number").val().trim();

          if (!ticket || !mobile) {
              $.alert({
                  title: 'Error',
                  content: '❌ Please enter both Ticket Number and Mobile Number.',
                  theme: 'dark'
              });
              return;
          }

          // AJAX request to verify order tracking details in the database
          $.ajax({
              url: "{{ route('track-order.search') }}",
              method: "POST",
              data: {
                  _token: "{{ csrf_token() }}",
                  ticket: ticket,
                  mobile: mobile
              },
              success: function(response) {
                  if (response.success) {
                      let statusHtml = '';
                      if (response.status === 'dispatched') {
                          statusHtml = `<p style="font-size: 1.2rem; color: #28a745; font-weight: bold; text-align: center; margin-bottom: 10px;">
                                            🚚 STATUS: DISPATCHED
                                        </p>
                                        <p style="font-size: 0.95rem; color: var(--text-muted); text-align: center;">
                                            Your physical ticket has been printed and dispatched.<br>
                                            <strong>Tracking No:</strong> <span style="color: var(--primary-color);">${response.tracking_number || 'N/A'}</span><br>
                                            Expected delivery within 2-3 business days.
                                        </p>`;
                      } else if (response.status === 'paid') {
                          statusHtml = `<p style="font-size: 1.2rem; color: #17a2b8; font-weight: bold; text-align: center; margin-bottom: 10px;">
                                            💳 STATUS: PAID (PROCESSING)
                                        </p>
                                        <p style="font-size: 0.95rem; color: var(--text-muted); text-align: center;">
                                            Your payment has been received. Your physical ticket is currently being printed and prepared for dispatch.
                                        </p>`;
                      } else {
                          statusHtml = `<p style="font-size: 1.2rem; color: #ffc107; font-weight: bold; text-align: center; margin-bottom: 10px;">
                                            ⏳ STATUS: ${response.status.toUpperCase()}
                                        </p>
                                        <p style="font-size: 0.95rem; color: var(--text-muted); text-align: center;">
                                            Your order is currently pending payment or processing.
                                        </p>`;
                      }

                      $.confirm({
                          title: '🔍 Tracking Order...',
                          content: `
                              <div style="text-align: left; margin-top: 10px;">
                                  <p><strong>Ticket Number:</strong> <span style="color: var(--secondary-color);">${ticket}</span></p>
                                  <p><strong>Mobile Number:</strong> <span style="color: var(--secondary-color);">${mobile}</span></p>
                                  <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
                                  ${statusHtml}
                              </div>
                          `,
                          theme: 'dark',
                          buttons: {
                              ok: {
                                  text: 'Close',
                                  btnClass: 'btn-success'
                              }
                          }
                      });
                  } else {
                      $.alert({
                          title: 'No Order Found',
                          content: '❌ No booking records found matching this Ticket Number and Mobile Number. Please check your inputs.',
                          theme: 'dark'
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
