@extends('layouts.app')

@section('title', 'Congratulations Winner! | Kerala State Lotteries')

@section('styles')
  <!-- jQuery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <!-- Google Fonts for Certificate -->
  <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;900&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap" rel="stylesheet">
  <style>
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    .payment-grid-layout {
      display: grid;
      grid-template-columns: 1.2fr 1fr;
      gap: 30px;
    }

    @media (max-width: 768px) {
      .payment-grid-layout {
        grid-template-columns: 1fr;
      }
      .payment-col-right {
        border-left: none !important;
        padding-left: 0 !important;
        border-top: 1px solid #dee2e6;
        padding-top: 30px;
        margin-top: 10px;
      }
    }
    
    .payment-modal-box .form-control::placeholder {
      color: #adb5bd;
    }
    
    .close-btn-x {
      transition: color 0.2s ease;
    }
    .close-btn-x:hover {
      color: #ffc107 !important;
    }
    
    .upi-pill {
      transition: all 0.2s ease;
    }
    .upi-pill:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(15, 118, 110, 0.4);
    }
  </style>
@endsection

@section('content')
  <!-- Winner Header Area -->
  <div class="winner-header-area">
    <div class="balloon-container">
      <!-- SVG Balloons -->
      <svg class="balloon-svg" viewBox="0 0 100 150" fill="#ff5722">
        <ellipse cx="50" cy="60" rx="35" ry="45"/>
        <path d="M50 105 L45 115 L55 115 Z"/>
        <path d="M50 115 Q40 130 50 145" fill="none" stroke="#ffffff" stroke-width="2"/>
      </svg>
      <svg class="balloon-svg" viewBox="0 0 100 150" fill="#ffd700">
        <ellipse cx="50" cy="60" rx="35" ry="45"/>
        <path d="M50 105 L45 115 L55 115 Z"/>
        <path d="M50 115 Q60 135 50 150" fill="none" stroke="#ffffff" stroke-width="2"/>
      </svg>
      <svg class="balloon-svg" viewBox="0 0 100 150" fill="#00bfff">
        <ellipse cx="50" cy="60" rx="35" ry="45"/>
        <path d="M50 105 L45 115 L55 115 Z"/>
        <path d="M50 115 Q40 130 50 145" fill="none" stroke="#ffffff" stroke-width="2"/>
      </svg>
      <svg class="balloon-svg" viewBox="0 0 100 150" fill="#e91e63">
        <ellipse cx="50" cy="60" rx="35" ry="45"/>
        <path d="M50 105 L45 115 L55 115 Z"/>
        <path d="M50 115 Q60 135 50 150" fill="none" stroke="#ffffff" stroke-width="2"/>
      </svg>
    </div>

    <div class="container">
      <div class="live-indicator-wrapper" style="margin-bottom: 1.5rem;">
        <span class="live-dot"></span>
        <span>LIVE DRAW RESULT</span>
      </div>
      <h1 class="section-title" style="margin-bottom: 0.5rem; text-transform: none;">Hi <span style="color: var(--secondary-color);">{{ $fullname }}</span>, Congratulations!</h1>
      <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;">
        You have won the prize in the Kerala State Lottery. Your ticket matched the winning numbers.
      </p>
    </div>
  </div>

  <!-- Main Winner Details -->
  <section class="section" style="padding-top: 0;">
    <div class="container" style="max-width: 900px;">
      
      <div class="winner-details-card">
        <h3 style="color: var(--secondary-color); margin-bottom: 1.5rem; text-align: center; text-transform: uppercase; letter-spacing: 1px;">Draw Winning Details</h3>
        
        <!-- Big Claim Callout -->
        <div class="win-callout">
          <h2>YOU WIN {{ $winningAmount }}</h2>
          <p>Claim your lottery prize money instantly. The government-mandated registration fee applies.</p>
          <button id="openWithdrawalBtn" class="btn" style="padding: 1rem 2.5rem; font-size: 1.1rem; box-shadow: 0 8px 25px rgba(255, 87, 34, 0.5);">Withdrawal Now</button>
        </div>

        <table class="winner-info-table">
          <tr>
            <td class="label-cell">Customer Name</td>
            <td class="value-cell">{{ $fullname }}</td>
          </tr>
          <tr>
            <td class="label-cell">Mobile Number</td>
            <td class="value-cell">{{ $mobile }}</td>
          </tr>
          <tr>
            <td class="label-cell">Winning Ticket Number</td>
            <td class="value-cell" style="font-family: monospace; font-size: 1.3rem; color: var(--secondary-color);">{{ $ticket }}</td>
          </tr>
          <tr>
            <td class="label-cell">Lottery Name</td>
            <td class="value-cell">{{ $lotteryName }}</td>
          </tr>
          <tr>
            <td class="label-cell">Prize Category</td>
            <td class="value-cell"><span style="background: rgba(255,215,0,0.15); border: 1px solid var(--secondary-color); color: var(--secondary-color); padding: 4px 10px; border-radius: 6px; font-size: 0.9rem;">{{ $prizeCategory }}</span></td>
          </tr>
          <tr>
            <td class="label-cell">Winning Price</td>
            <td class="value-cell" style="color: #4caf50; font-weight: 800; font-size: 1.3rem;">{{ $winningAmount }}</td>
          </tr>
          <tr>
            <td class="label-cell">Draw Date & Time</td>
            <td class="value-cell">{{ $drawDate }}</td>
          </tr>
        </table>
      </div>

      <!-- Winner Certificate Section -->
      <div class="certificate-wrapper">
        <div class="certificate-box">
          <div class="cert-header">Government of Kerala</div>
          <div class="cert-logo-title">Kerala State Lotteries</div>
          
          <div class="cert-ribbon-container">
            <div class="cert-ribbon">Winner Certificate</div>
          </div>

          <div class="cert-body-text">This is to officially certify and honor</div>
          <div class="cert-name">{{ $fullname }}</div>
          
          <div class="cert-desc">
            As the official holder of ticket <strong>{{ $ticket }}</strong>, who has successfully won the <strong>{{ $prizeCategory }}</strong> under the draw date of <strong>{{ $drawDate }}</strong>, entitling the recipient to the sum of <strong>{{ $winningAmount }}</strong>.
          </div>

          <div class="cert-footer-grid">
            <div class="cert-footer-col">
              <span class="cert-date-val">{{ now()->format('d-m-Y') }}</span>
              <div class="cert-signature-line">Date of Issue</div>
            </div>
            <div class="cert-footer-col">
              <div class="cert-stamp-seal">
                <div class="cert-stamp-inner">
                  Kerala<br>Lotto<br>Seal
                </div>
              </div>
            </div>
            <div class="cert-footer-col">
              <!-- Inline SVG for director signature representation -->
              <svg class="cert-sig-img" viewBox="0 0 100 50" fill="none" stroke="#2b2b2b" stroke-width="1.5">
                <path d="M10 30 C 20 10, 40 40, 50 20 C 60 10, 70 35, 90 25 M30 30 C45 15, 50 35, 75 10"/>
              </svg>
              <div class="cert-signature-line">Director Signature</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bumper Promo Banner -->
      <div class="bumper-banner-container">
        <div class="bumper-banner-box">
          <div class="bumper-grid">
            <div class="bumper-left">
              <h2>Kerala Bumper Lottery</h2>
              <p>Check out the mega prizes for this season's grand bumper draw. Don't miss your next chance!</p>
              <div class="prize-tags-grid">
                <div class="prize-tag-item">
                  <div class="title">1st Prize</div>
                  <div class="amount">₹12 Crore</div>
                </div>
                <div class="prize-tag-item">
                  <div class="title">2nd Prize</div>
                  <div class="amount">₹1 Crore</div>
                </div>
                <div class="prize-tag-item">
                  <div class="title">3rd Prize</div>
                  <div class="amount">₹10 Lakhs</div>
                </div>
                <div class="prize-tag-item">
                  <div class="title">4th Prize</div>
                  <div class="amount">₹5 Lakhs</div>
                </div>
              </div>
            </div>
            <div class="bumper-right">
              <div class="bumper-ticket-highlight">
                <span class="ticket-lbl">YOUR WINNING TICKET</span>
                <span class="ticket-val">{{ $ticket }}</span>
                <span class="prize-val">{{ $winningAmount }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Live Results Board -->
      @if (count($otherResults) > 0)
        <div class="results-board-container">
          <div class="board-card">
            <h3 style="color: var(--secondary-color); text-align: center; margin-bottom: 1.5rem; text-transform: uppercase;">Live Draw Results Board</h3>
            <p style="color: var(--text-muted); text-align: center; font-size: 0.95rem; margin-bottom: 2rem;">
              Here are all the winning numbers drawn for this date. Your ticket is highlighted below in <span style="color: var(--primary-color); font-weight: 700;">orange</span>.
            </p>
            
            <div class="board-groups-grid">
              @foreach ($otherResults->groupBy('prize_category') as $category => $items)
                <div class="board-group-item">
                  <div class="board-group-header">
                    <span>{{ $category }}</span>
                    <span class="prize-amt">{{ $items->first()->winning_amount }}</span>
                  </div>
                  <div class="board-badge-list">
                    @foreach ($items as $item)
                      <div class="board-badge {{ strtolower(trim($item->winning_number)) === strtolower(trim($ticket)) ? 'winning-badge-highlight' : '' }}">
                        {{ $item->winning_number }}
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif

    </div>
  </section>

  <!-- 1. Account Details Modal -->
  <div id="accountDetailsModal" class="payment-modal">
    <div class="payment-modal-box" style="padding: 0; border: none; overflow: hidden; background: #fff; max-width: 500px; width: 100%;">
      <!-- Green Header -->
      <div style="background-color: #198754; color: #ffffff; padding: 1.25rem; position: relative; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700; font-family: 'Outfit', sans-serif;">Enter Your Account Details</h3>
        <button type="button" class="close-btn-x" id="closeAccountDetailsModal" style="background: none; border: none; color: #ffffff; font-size: 1.8rem; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
      </div>
      <!-- Body -->
      <div style="padding: 2rem; color: #333333; text-align: left; max-height: 75vh; overflow-y: auto;">
        <form id="accountDetailsForm">
          <div class="form-group" style="margin-bottom: 1.25rem;">
            <label style="color: #495057; display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.95rem;">Account Holder Name</label>
            <input type="text" id="acc_name" class="form-control" placeholder="Account Holder Name" required style="background: #ffffff; border: 1px solid #ced4da; color: #333333; font-size: 1rem; padding: 0.75rem 1rem;">
          </div>
          <div class="form-group" style="margin-bottom: 1.25rem;">
            <label style="color: #495057; display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.95rem;">Account Number</label>
            <input type="text" id="acc_num" class="form-control" placeholder="123456" required style="background: #ffffff; border: 1px solid #ced4da; color: #333333; font-size: 1rem; padding: 0.75rem 1rem;">
          </div>
          <div class="form-group" style="margin-bottom: 1.25rem;">
            <label style="color: #495057; display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.95rem;">IFSC Code</label>
            <input type="text" id="acc_ifsc" class="form-control" placeholder="IFSC Code" required style="background: #ffffff; border: 1px solid #ced4da; color: #333333; font-size: 1rem; padding: 0.75rem 1rem;">
          </div>
          <div class="form-group" style="margin-bottom: 1.75rem;">
            <label style="color: #495057; display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.95rem;">Bank Name</label>
            <input type="text" id="acc_bank" class="form-control" placeholder="Bank Name" required style="background: #ffffff; border: 1px solid #ced4da; color: #333333; font-size: 1rem; padding: 0.75rem 1rem;">
          </div>
          
          <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem; font-weight: 700; background: linear-gradient(45deg, #f0ad4e, #ffb22b); color: #0d1b2a; border-radius: 8px; border: none; cursor: pointer; text-transform: none; box-shadow: 0 4px 15px rgba(240, 173, 78, 0.2); transition: all 0.3s ease;">Continue to Withdrawal</button>
        </form>
      </div>
    </div>
  </div>

  <!-- 2. Payment Details Modal -->
  <div id="paymentDetailsModal" class="payment-modal">
    <div class="payment-modal-box" style="padding: 0; border: none; overflow: hidden; background: #fff; max-width: 850px; width: 100%;">
      <!-- Header -->
      <div style="border-bottom: 1px solid #dee2e6; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #333; font-family: 'Outfit', sans-serif; text-transform: none;">Payment Details</h3>
        <button type="button" class="close-btn-x" id="closePaymentDetailsModal" style="background: none; border: none; color: #333; font-size: 1.8rem; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
      </div>
      <!-- Body -->
      <form id="claimSubmitForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="ticket" value="{{ $ticket }}">
        <input type="hidden" name="mobile" value="{{ $mobile }}">
        <div class="payment-modal-body" style="padding: 1.5rem 2rem; color: #333; text-align: left; max-height: 75vh; overflow-y: auto;">
          <div class="payment-grid-layout">
            <!-- Left Column: Instructions -->
            <div class="payment-col-left">
              <h4 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">Instructions</h4>
              <p style="margin-bottom: 0.5rem; font-size: 0.95rem; color: #495057;">Dear {{ $fullname }},</p>
              <p style="margin-bottom: 0.5rem; font-size: 0.95rem; color: #495057;">Ticket: <strong>{{ $ticket }}</strong></p>
              <p style="margin-bottom: 1.25rem; font-size: 0.95rem; color: #495057;">Win: <strong>{{ $winningAmount }}</strong></p>
              
              <p style="font-size: 0.95rem; line-height: 1.6; color: #495057; margin-bottom: 1.5rem;">
                Please pay the Kerala state Registration Charges fee of <strong style="color: #000;">₹ {{ number_format($registrationFee) }}</strong>. After verification, your winning amount will be transferred to your account within 30 minutes.
              </p>
              
              <div style="margin-bottom: 1.5rem;">
                <!-- Hidden file upload -->
                <input type="file" id="proofScreenshot" name="screenshot" accept="image/*" style="display: none;" required>
                <!-- Upload trigger button style to WhatsApp -->
                <button type="button" id="whatsappUploadBtn" class="btn" style="background-color: #198754; color: #ffffff; padding: 0.75rem 1.25rem; border-radius: 6px; font-weight: 700; width: 100%; border: none; font-size: 0.95rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 10px rgba(25, 135, 84, 0.2); text-transform: none; transition: all 0.3s ease;">
                  <svg style="width: 18px; height: 18px; fill: currentColor;" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.457L0 24zm6.59-4.846c1.6.95 3.498 1.452 5.42 1.453 5.485 0 9.95-4.466 9.954-9.953.002-2.657-1.03-5.155-2.905-7.03C17.24 1.74 14.743.708 12.01.708c-5.49 0-9.953 4.467-9.957 9.953-.002 2.025.528 4.004 1.537 5.728l-.993 3.633 3.72-.975zm10.702-7.234c-.29-.145-1.716-.848-1.982-.944-.265-.096-.458-.145-.65.145-.193.29-.748.944-.916 1.137-.168.193-.337.217-.627.072-1.29-.646-2.13-1.11-2.982-2.573-.227-.39.227-.362.648-1.2.07-.144.035-.27-.018-.378-.052-.107-.458-1.104-.626-1.51-.165-.398-.333-.343-.458-.35-.118-.006-.254-.007-.39-.007-.136 0-.356.05-.542.254-.187.203-.712.696-.712 1.7s.73 1.972.83 2.106c.1.135 1.437 2.193 3.48 3.076.486.21 1.05.337 1.442.46.49.155.937.133 1.29.08.394-.06 1.2-.492 1.37-.965.172-.474.172-.88.12-.966-.05-.085-.19-.133-.48-.278z"/>
                  </svg>
                  <span id="whatsappBtnText">Upload Screenshot via WhatsApp</span>
                </button>
                <div id="fileSelectedInfo" style="display: none; margin-top: 8px; font-size: 0.85rem; color: #198754; font-weight: bold; text-align: center;"></div>
              </div>

              <!-- Action Button after selecting screenshot -->
              <div id="submitClaimBtnContainer" style="display: none; margin-bottom: 1.5rem;">
                <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; font-size: 1rem; font-weight: 700; background: linear-gradient(45deg, #ff5722, #ff9800); color: #fff; border-radius: 6px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                  <span id="claimSubmitBtnText">Submit Payment Proof</span>
                  <span id="claimSubmitSpinner" style="display: none; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s linear infinite;"></span>
                </button>
              </div>

              <ul class="inst-bullets" style="padding-left: 1.25rem; font-size: 0.85rem; color: #495057; line-height: 1.6; margin: 0;">
                <li style="margin-bottom: 0.4rem;">Amount deposited within 30 minutes post verification.</li>
                <li style="margin-bottom: 0.4rem;">No cash deposits allowed.</li>
                <li style="margin-bottom: 0.4rem;">Registration fee is refundable per process.</li>
                <li style="margin-bottom: 0;">For help, email: support@jackpot.keralastateslotterys.com</li>
              </ul>
            </div>

            <!-- Right Column: Payment Details -->
            <div class="payment-col-right" style="border-left: 1px solid #dee2e6; padding-left: 30px;">
              <div style="text-align: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 2.2rem; font-weight: 800; color: #212529; margin-bottom: 1rem; font-family: 'Outfit', sans-serif;">₹ {{ number_format($registrationFee) }}</h2>
                <div style="width: 180px; height: 180px; margin: 0 auto 1rem; border: 1px solid #ced4da; padding: 8px; border-radius: 8px;">
                  <img src="{{ $qrCode }}" alt="UPI QR Code" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <p style="font-size: 0.85rem; color: #6c757d; font-weight: 600;">Scan & pay using any UPI app or click below.</p>
              </div>

              <div style="margin-bottom: 1.5rem;">
                <span style="display: block; font-size: 0.85rem; color: #495057; font-weight: 700; margin-bottom: 0.5rem; text-align: center; text-transform: uppercase; letter-spacing: 0.5px;">Pay via Any of the Following</span>
                @php
                  $upiPaymentUrl = "upi://pay?pa=" . $upiId . "&pn=Kerala%20State%20Lotteries&am=" . $registrationFee . "&cu=INR";
                @endphp
                <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 1rem;">
                  <a href="{{ $upiPaymentUrl }}" class="upi-pill" style="background-color: #0f766e; color: #ffffff; padding: 6px 14px; border-radius: 50px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-block;">GPay</a>
                  <a href="{{ $upiPaymentUrl }}" class="upi-pill" style="background-color: #0f766e; color: #ffffff; padding: 6px 14px; border-radius: 50px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-block;">PhonePe</a>
                  <a href="{{ $upiPaymentUrl }}" class="upi-pill" style="background-color: #0f766e; color: #ffffff; padding: 6px 14px; border-radius: 50px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: inline-block;">Paytm</a>
                </div>
              </div>

              <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8rem; color: #6c757d; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;">Send payment to this number</label>
                <div style="display: flex; gap: 5px;">
                  <input type="text" class="form-control" value="{{ $upiId }}" readonly style="background: #f8f9fa; border: 1px solid #ced4da; color: #495057; padding: 0.5rem; font-size: 0.9rem; border-radius: 4px; flex: 1;">
                  <button type="button" class="btn" onclick="copyToClipboard('{{ $upiId }}', this)" style="background: #e9ecef; border: 1px solid #ced4da; color: #495057; padding: 0.5rem 0.85rem; border-radius: 4px; font-size: 0.85rem; text-transform: none; font-weight: 600; box-shadow: none; min-width: 70px;">Copy</button>
                </div>
              </div>

              <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.8rem; color: #6c757d; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;">UPI ID</label>
                <div style="display: flex; gap: 5px;">
                  <input type="text" class="form-control" value="{{ $upiId }}" readonly style="background: #f8f9fa; border: 1px solid #ced4da; color: #495057; padding: 0.5rem; font-size: 0.9rem; border-radius: 4px; flex: 1;">
                  <button type="button" class="btn" onclick="copyToClipboard('{{ $upiId }}', this)" style="background: #e9ecef; border: 1px solid #ced4da; color: #495057; padding: 0.5rem 0.85rem; border-radius: 4px; font-size: 0.85rem; text-transform: none; font-weight: 600; box-shadow: none; min-width: 70px;">Copy</button>
                </div>
              </div>

              <!-- Bank Transfer Section -->
              <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1rem; background: #fafafa;">
                <h5 style="margin: 0 0 0.75rem 0; font-size: 0.95rem; font-weight: 800; color: #333; text-transform: uppercase; border-bottom: 1px solid #dee2e6; padding-bottom: 0.5rem; text-align: center;">Bank Transfer</h5>
                <div style="font-size: 0.85rem; color: #495057; display: flex; flex-direction: column; gap: 6px;">
                  <div style="display: flex; justify-content: space-between;"><strong>Bank:</strong> <span>{{ $bankName }}</span></div>
                  <div style="display: flex; justify-content: space-between;"><strong>Account Name:</strong> <span>{{ $bankAccountName }}</span></div>
                  <div style="display: flex; justify-content: space-between;"><strong>Account No.:</strong> <span>{{ $bankAccountNo }}</span></div>
                  <div style="display: flex; justify-content: space-between;"><strong>IFSC:</strong> <span>{{ $bankIfsc }}</span></div>
                </div>
                <p style="font-size: 0.75rem; color: #6c757d; margin: 8px 0 0 0; text-align: center; font-style: italic;">Complete your transfer and keep your Transaction/UTR ID for confirmation.</p>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <!-- jQuery & jQuery Confirm -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script>
    // Copy function
    function copyToClipboard(text, element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val(text).select();
      document.execCommand("copy");
      $temp.remove();
      
      let originalText = $(element).html();
      $(element).text("Copied!");
      setTimeout(function() {
        $(element).html(originalText);
      }, 1500);
    }

    $(document).ready(function() {
      // Step 1: Open Account Details modal
      $("#openWithdrawalBtn").click(function() {
        $("#accountDetailsModal").addClass("active");
      });

      // Close Account Details modal
      $("#closeAccountDetailsModal").click(function() {
        $("#accountDetailsModal").removeClass("active");
      });

      // Step 2: Handle Account Details Submit -> Continue to Withdrawal -> Show Payment Details modal
      $("#accountDetailsForm").submit(function(e) {
        e.preventDefault();
        
        // Hide Step 1 modal, open Step 2 modal
        $("#accountDetailsModal").removeClass("active");
        $("#paymentDetailsModal").addClass("active");
      });

      // Close Payment Details modal
      $("#closePaymentDetailsModal").click(function() {
        $("#paymentDetailsModal").removeClass("active");
      });

      // Close modals on click outside
      $(".payment-modal").click(function(e) {
        if ($(e.target).hasClass("payment-modal")) {
          $(this).removeClass("active");
        }
      });

      // Trigger file upload when clicking "Upload Screenshot via WhatsApp"
      $("#whatsappUploadBtn").click(function() {
        $("#proofScreenshot").click();
      });

      // Show selected file name and enable main submit
      $("#proofScreenshot").change(function(e) {
        if (e.target.files && e.target.files[0]) {
          let filename = e.target.files[0].name;
          $("#fileSelectedInfo").text("Selected File: " + filename).show();
          $("#whatsappBtnText").text("Change Screenshot");
          $("#submitClaimBtnContainer").show();
        } else {
          $("#fileSelectedInfo").hide();
          $("#whatsappBtnText").text("Upload Screenshot via WhatsApp");
          $("#submitClaimBtnContainer").hide();
        }
      });

      // Submit payment claim form via AJAX
      $("#claimSubmitForm").submit(function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $("#claimSubmitBtnText").text("Verifying Payment...");
        $("#claimSubmitSpinner").show();
        $("#claimSubmitForm button[type='submit']").attr("disabled", true);

        $.ajax({
          url: "{{ route('results.claim') }}",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $("#claimSubmitSpinner").hide();
            $("#claimSubmitBtnText").text("Submit Payment Proof");
            $("#claimSubmitForm button[type='submit']").attr("disabled", false);
            
            if (response.success) {
              $("#paymentDetailsModal").removeClass("active");
              
              $.confirm({
                title: 'Success!',
                content: `
                  <div style="text-align: center; margin-top: 10px;">
                    <h3 style="color: #4caf50; margin-bottom: 15px;">Claim Registered</h3>
                    <p style="font-size: 1.05rem;">Your screenshot has been received and verified.</p>
                    <p style="font-size: 0.95rem; color: var(--text-muted); margin-top: 15px;">
                      Your lottery winning amount is being processed and will be transferred to your account shortly.
                    </p>
                  </div>
                `,
                theme: 'dark',
                buttons: {
                  ok: {
                    text: 'Done',
                    btnClass: 'btn-success',
                    action: function() {
                      window.location.href = "{{ route('results') }}";
                    }
                  }
                }
              });
            } else {
              $.alert({
                title: 'Error',
                content: '❌ ' + (response.message || 'Something went wrong.'),
                theme: 'dark'
              });
            }
          },
          error: function(xhr) {
            $("#claimSubmitSpinner").hide();
            $("#claimSubmitBtnText").text("Submit Payment Proof");
            $("#claimSubmitForm button[type='submit']").attr("disabled", false);
            
            let errMsg = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errMsg = xhr.responseJSON.message;
            }
            
            $.alert({
              title: 'Error',
              content: '❌ ' + errMsg,
              theme: 'dark'
            });
          }
        });
      });
    });
  </script>
@endsection
