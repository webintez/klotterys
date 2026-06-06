@extends('layouts.app')

@section('title', 'Kerala State Lotteries | About Us')

@section('content')
  <!-- Page Header -->
  <section class="section" style="padding-bottom: 2rem;">
    <div class="container">
      <h1 class="section-title">About Us</h1>
    </div>
  </section>

  <!-- About Content -->
  <section class="section bg-alt" style="padding-top: 3rem;">
    <div class="container" style="max-width: 800px; text-align: center;">
      <p style="font-size: 1.2rem; color: var(--text-color); margin-bottom: 2rem;">
        Welcome to the Directorate of Kerala State Lotteries! We are a government-run enterprise dedicated to providing transparent, fair, and exciting lottery experiences for millions of players.
      </p>
      
      <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <div class="card" style="text-align: left; margin-bottom: 2rem; flex: 1; min-width: 300px;">
          <h3 style="color: var(--primary-color);">Our History</h3>
          <p>Kerala, God's own country, added another first to its cap in 1967 when a department was set up in the Government sector for the first time in India for the conduct of paper lotteries. It was late Shri. P. K. Kunju Sahib who envisaged this idea for the generation of revenue through the sale of lotteries.</p>
        </div>

        <div class="card" style="text-align: left; margin-bottom: 2rem; flex: 1; min-width: 300px;">
          <h3 style="color: var(--secondary-color);">Our Mission</h3>
          <p>Our mission is to provide a stable source of income to the poor and needy belonging to the marginalized sections of society, while offering life-changing opportunities for our players.</p>
        </div>
      </div>
      
      <a href="{{ route('contact') }}" class="btn">Get In Touch</a>
    </div>
  </section>
@endsection
