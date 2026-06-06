@extends('layouts.app')

@section('title', 'Kerala State Lotteries | Buy Tickets')

@section('styles')
  <style>
    body {
        padding-bottom: 90px;
    }
    .checkbox-budget {
        display: none;
    }
    .checkbox-budget + label {
        display: inline-block;
        padding: 6px 11px;
        margin: 4px;
        border-radius: 6px;
        cursor: pointer;
        background: #353746;
        color: #fff;
        font-weight: 600;
    }
    .checkbox-budget:checked + label {
        background: linear-gradient(138deg, #da2c4d, #f8ab37);
        color: #fff;
    }
    .fixed-bottom-btn {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #1f2029;
        padding: 10px;
        z-index: 999;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.5);
        text-align: center;
    }
    .fixed-bottom-btn button {
        width: 100%;
        max-width: 600px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: bold;
        background: #28a745;
        color: #fff;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
    .card-img-container {
        background: #fff;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        margin-bottom: 10px;
    }
    .card-img-container img {
        width: 100%;
        display: block;
    }
    .ticket-header {
        color: #ffc107;
        font-size: 1.25rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    .badge-price {
        background: #17a2b8;
        color: #fff;
        padding: 0.25em 0.4em;
        border-radius: 0.25rem;
        font-size: 75%;
        font-weight: 700;
        vertical-align: baseline;
    }
  </style>
  <!-- jQuery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('content')
  <!-- Page Header -->
  <section class="section" style="padding-bottom: 2rem;">
    <div class="container">
      <h1 class="section-title">Buy Lottery Tickets</h1>
      <p style="text-align: center; color: var(--text-muted); max-width: 600px; margin: 0 auto;">
        Purchase genuine Kerala State Lottery tickets online. Choose from weekly draws to massive Bumper jackpots.
      </p>
    </div>
  </section>

  <!-- Tickets Selection -->
  <section class="section" style="padding-top: 3rem; background-color: #0d1b2a;">
    <div class="container" style="max-width: 800px;">
      
      <!-- PREMIUM -->
      <div style="margin-bottom: 2rem;">
          <div class="card-img-container">
              <img src="https://jackpot.keralastateslotterys.com/Home/premiumticket.jpg" alt="Premium Ticket">
          </div>
          <div class="ticket-header">
              🎟 Premium Lottery Ticket Price <span class="badge-price">₹500</span>
          </div>
          <div id="a-series"></div>    
      </div>
      
      <!-- STANDARD -->
      <div style="margin-bottom: 2rem;">
          <div class="card-img-container">
              <img src="https://jackpot.keralastateslotterys.com/Home/standardticket.jpg" alt="Standard Ticket">
          </div>
          <div class="ticket-header">
              🎟 Standard Lottery Ticket Price <span class="badge-price">₹149</span>
          </div>
          <div id="b-series"></div>
      </div>
      
      <!-- BASIC -->
      <div style="margin-bottom: 2rem;">
          <div class="card-img-container">
              <img src="https://jackpot.keralastateslotterys.com/Home/basicticket.jpg" alt="Basic Ticket">
          </div>
          <div class="ticket-header">
              🎟 Basic Lottery Ticket Price <span class="badge-price">₹40</span>
          </div>
          <div id="c-series"></div>
      </div>

    </div>
  </section>

  <!-- FIXED BOTTOM BUTTON -->
  <div class="fixed-bottom-btn">
      <button id="confirmTickets">
          Confirm Selected Tickets
      </button>
  </div>
@endsection

@section('scripts')
  <!-- jQuery & jQuery Confirm -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script>
  /* GENERATE UNIQUE TICKETS */
  function generateSeries(prefix, count){
      let set = new Set();
      while(set.size < count){
          let num = Math.floor(Math.random() * 900000) + 100000;
          set.add(prefix + num);
      }
      return Array.from(set);
  }

  const aSeries = generateSeries("VL", 50); // Premium
  const bSeries = generateSeries("SL", 50); // Standard
  const cSeries = generateSeries("KL", 50); // Basic

  /* RENDER CHECKBOXES */
  function renderSeries(series, container){
      let html = "";
      series.forEach((num, i)=>{
          let id = container + "_" + i;
          html += `
              <input type="checkbox" class="checkbox-budget" id="${id}" value="${num}">
              <label for="${id}">${num}</label>
          `;
      });
      document.getElementById(container).innerHTML = html;
  }

  renderSeries(aSeries,"a-series");
  renderSeries(bSeries,"b-series");
  renderSeries(cSeries,"c-series");

  /* CONFIRM MULTIPLE TICKETS */
  $("#confirmTickets").click(function(){
      let selected = [];
      $(".checkbox-budget:checked").each(function(){
          selected.push($(this).val());
      });

      if(selected.length === 0){
          $.alert({
              title: 'Error',
              content: '❌ Please select at least one ticket',
              theme: 'dark'
          });
          return;
      }

      $.confirm({
          title: 'Confirm Tickets',
          content: 'Your Selected Tickets:<br><br><b style="color: var(--primary-color); word-break: break-all;">' + selected.join(", ") + '</b>',
          theme: 'dark',
          buttons:{
              confirm:{
                  text:'Confirm',
                  btnClass:'btn-success',
                  action:function(){
                      window.location.href = "{{ route('book-ticket') }}?ticket=" + selected.join(",");
                  }
              },
              cancel:{
                  text:'Cancel'
              }
          }
      });
  });
  </script>
@endsection
