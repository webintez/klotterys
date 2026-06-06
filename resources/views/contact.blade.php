@extends('layouts.app')

@section('title', 'Kerala State Lotteries | Contact Us')

@section('content')
  <!-- Page Header -->
  <section class="section" style="padding-bottom: 2rem;">
    <div class="container">
      <h1 class="section-title">Contact Us</h1>
    </div>
  </section>

  <!-- Contact Form -->
  <section class="section bg-alt" style="padding-top: 3rem;">
    <div class="container grid-3">
      
      <!-- Contact Details -->
      <div class="card" style="text-align: left;">
        <h3 style="color: var(--primary-color);">Get In Touch</h3>
        <p><strong>Address:</strong> Directorate of State Lotteries, Vikas Bhavan, Thiruvananthapuram, Kerala</p>
        <p><strong>Email:</strong> support@keralalotteries.com</p>
        <p><strong>Phone:</strong> +91 471 2305230</p>
        <div style="margin-top: 2rem;">
          <h4 style="color: var(--secondary-color); margin-bottom: 0.5rem;">Working Hours</h4>
          <p>Mon - Sat: 10:00 AM - 5:00 PM</p>
        </div>
      </div>

      <!-- Form -->
      <div class="card" style="grid-column: span 2;">
        <h3>Send us a Message</h3>
        <form action="#" method="POST" style="margin-top: 2rem; text-align: left;">
          @csrf
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Your Name" required>
          </div>
          <div class="form-group">
            <input type="email" class="form-control" placeholder="Your Email Address" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Subject" required>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
          </div>
          <button type="submit" class="btn">Send Message</button>
        </form>
      </div>

    </div>
  </section>
@endsection
