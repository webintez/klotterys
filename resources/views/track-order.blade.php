@extends('layouts.app')

@section('title', 'Kerala State Lotteries | Track Order')

@section('styles')
  <!-- jQuery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('content')
  <!-- Page Header -->
  <section class="section" style="padding-bottom: 2rem;">
    <div class="container">
      <h1 class="section-title">Track Your Order</h1>
    </div>
  </section>

  <!-- Track Order Form -->
  <section class="section bg-alt" style="padding-top: 3rem;">
    <div class="container" style="max-width: 600px;">
      
      <div class="card" style="text-align: left;">
        <h3 style="color: var(--secondary-color); text-align: center; margin-bottom: 2rem;">Where is my ticket?</h3>
        
        <form id="trackForm">
          @csrf
          <div class="form-group">
            <label for="ticket_number" style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Ticket Number:</label>
            <input type="text" id="ticket_number" class="form-control" placeholder="e.g. VL-123456" required style="font-size: 1.1rem; text-align: center;">
          </div>
          <div class="form-group" style="margin-top: 1.5rem;">
            <label for="mobile_number" style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Mobile Number:</label>
            <input type="tel" id="mobile_number" class="form-control" placeholder="e.g. 9876543210" required style="font-size: 1.1rem; text-align: center;">
          </div>
          <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn" style="width: 100%;">Track Status</button>
          </div>
        </form>

        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color); text-align: center;">
          <p style="color: var(--text-muted); font-size: 0.9rem;">
            Having trouble tracking your order? Please have your order details ready and <a href="{{ route('contact') }}" style="color: var(--primary-color);">contact support</a>.
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
