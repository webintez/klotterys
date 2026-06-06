@extends('layouts.app')

@section('title', 'Kerala State Lotteries | Home')

@section('styles')
<style>
  .partners-carousel-container {
    overflow: hidden;
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem 0;
    border-radius: 15px;
    width: 100%;
    position: relative;
    display: flex;
    align-items: center;
  }
  .partners-carousel-container::before,
  .partners-carousel-container::after {
    content: "";
    height: 100%;
    position: absolute;
    top: 0;
    width: 100px;
    z-index: 2;
    pointer-events: none;
  }
  .partners-carousel-container::before {
    left: 0;
    background: linear-gradient(to right, var(--bg-color), transparent);
  }
  .partners-carousel-container::after {
    right: 0;
    background: linear-gradient(to left, var(--bg-color), transparent);
  }
  .partners-carousel-track {
    display: flex;
    gap: 4rem;
    width: max-content;
    animation: scrollLogos 35s linear infinite;
  }
  .partners-carousel-track img {
    height: 50px;
    object-fit: contain;
    opacity: 0.8;
    transition: opacity 0.25s ease, transform 0.25s ease;
    flex-shrink: 0;
  }
  .partners-carousel-track img:hover {
    opacity: 1;
    transform: scale(1.05);
  }
  @keyframes scrollLogos {
    0% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(-50%);
    }
  }

  /* Hero Slider Styles */
  .hero-slider {
    position: relative;
    width: 100%;
    max-width: 500px;
    height: 0;
    padding-bottom: 70.4%; /* Aspect ratio fallback */
    margin: 0 0 0 auto;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    background-color: rgba(255,255,255,0.02);
  }
  .slider-track {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
  .slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    z-index: 1;
  }
  .slide.active {
    opacity: 1;
    z-index: 2;
  }
  .slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px;
  }
  .slider-dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
    background: rgba(0, 0, 0, 0.4);
    padding: 6px 12px;
    border-radius: 20px;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
  }
  .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .dot.active {
    background: var(--primary-color);
    transform: scale(1.2);
  }
  @media (max-width: 768px) {
    .hero-slider {
      margin: 0 auto;
      height: 0;
      padding-bottom: 70.4%;
    }
  }
</style>
@endsection

@section('content')
  <!-- Hero Section -->
  <section class="hero container">
    <div class="hero-image">
      <div class="hero-slider">
        <div class="slider-track">
          <div class="slide active">
            <img src="{{ asset('images/photo_6147822454307926542_y.jpg') }}" alt="Jackpot Banner">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.04.jpeg') }}" alt="Jackpot Banner 1">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.05 (1).jpeg') }}" alt="Jackpot Banner 2">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.05.jpeg') }}" alt="Jackpot Banner 3">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.06 (1).jpeg') }}" alt="Jackpot Banner 4">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.06.jpeg') }}" alt="Jackpot Banner 5">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.07 (1).jpeg') }}" alt="Jackpot Banner 6">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.07 (2).jpeg') }}" alt="Jackpot Banner 7">
          </div>
          <div class="slide">
            <img src="{{ asset('images/WhatsApp Image 2026-06-06 at 18.02.07.jpeg') }}" alt="Jackpot Banner 8">
          </div>
        </div>
        <div class="slider-dots">
          <span class="dot active"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
    </div>
    <div class="hero-content">
      <h1>Win the Ultimate <br><span style="color: var(--primary-color);">Jackpot</span></h1>
      <p>Experience the excitement of Kerala State Lotteries. Play today, change your life tomorrow. Fast, secure, and 100% genuine.</p>
      <a href="{{ route('buy-tickets') }}" class="btn">Play Now</a>
      <a href="{{ route('results') }}" class="btn btn-secondary" style="margin-left: 1rem;">Check Results</a>
    </div>
  </section>

  <!-- Weekly Lotteries Section -->
  <section class="section bg-alt">
    <div style="padding: 0 1rem;">
      <h2 class="section-title" style="text-align: center; margin-bottom: 2rem; font-size: 3rem; font-weight: 800; text-transform: uppercase;">Our Weekly Lotteries</h2>
      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; justify-items: center;">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/bhagyadhara.jpg" alt="Bhagyadhara" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/sthreesakthi.jpg" alt="Sthree Sakthi" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/dhanalakshmi.jpg" alt="Dhanalakshmi" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/karunya-plus.jpg" alt="Karunya Plus" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/suvarnna-keralam.jpg" alt="Suvarnna Keralam" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/karunya.jpg" alt="Karunya" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <img src="https://jackpot.keralastateslotterys.com/images/weekely-lotteries/new-lotteries/samrudhi.jpg" alt="Samrudhi" style="width: 100%; border-radius: 10px; border: 2px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
      </div>
    </div>
  </section>

  <!-- Quick Links Section -->
  <section class="section">
    <div class="container">
      <h2 class="section-title">Why Play With Us?</h2>
      <div class="grid-3">
        <div class="card">
          <h3>Official & Secure</h3>
          <p>We provide 100% genuine Kerala State Lottery tickets directly from authorized distributors.</p>
        </div>
        <div class="card">
          <h3>Instant Results</h3>
          <p>Get notified immediately after the draw. Check results anytime from anywhere.</p>
        </div>
        <div class="card">
          <h3>Easy Tracking</h3>
          <p>Track your physical ticket dispatch easily with our robust online tracking system.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Directorate Section -->
  <section class="section bg-alt">
    <div class="container">
      <div style="margin-bottom: 3rem; text-align: center; max-width: 800px; margin-left: auto; margin-right: auto;">
        <h2 class="section-title">Directorate of Kerala State Lotteries</h2>
        <p style="color: var(--text-muted); margin-bottom: 1rem; font-size: 1.1rem;">
          Welcome to Kerala State Lotteries. Kerala, God's own country, added another first to its cap in 1967 when a Department was setup in the Government sector for the first time in India for the conduct of paper Lotteries.
        </p>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
          It was late Shri. P. K. Kunju Sahib, who envisaged this idea for the generation of revenue through the sale of lotteries and for providing a stable source of income to the poor and needy belonging to the marginalized section of society.
        </p>
        <a href="{{ route('about') }}" class="btn btn-secondary">Read More About Us</a>
      </div>
    </div>
  </section>

  <!-- Partners Section -->
  <section class="section">
    <div class="container">
      <div class="partners-carousel-container">
        <div class="partners-carousel-track">
          <!-- Set 1 -->
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/kerala-gov-1.png" alt="Kerala Gov">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/india-gov-1.png" alt="India Gov">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/kerala-taxes-1.png" alt="Kerala Taxes">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/keltron.png" alt="Keltron">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/keralastate-itmission-1.png" alt="IT Mission">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/cdit-1.png" alt="CDIT">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/nic-1.png" alt="NIC">
          
          <!-- Set 2 (Duplicate for loop) -->
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/kerala-gov-1.png" alt="Kerala Gov">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/india-gov-1.png" alt="India Gov">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/kerala-taxes-1.png" alt="Kerala Taxes">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/keltron.png" alt="Keltron">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/keralastate-itmission-1.png" alt="IT Mission">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/cdit-1.png" alt="CDIT">
          <img src="https://jackpot.keralastateslotterys.com/images/link-icons/nic-1.png" alt="NIC">
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.hero-slider .slide');
    const dots = document.querySelectorAll('.hero-slider .dot');
    let currentSlide = 0;
    const slideInterval = 4000; // 4 seconds

    function showSlide(index) {
      if (slides.length === 0) return;
      slides.forEach((slide, i) => {
        if (i === index) {
          slide.classList.add('active');
          if (dots[i]) dots[i].classList.add('active');
        } else {
          slide.classList.remove('active');
          if (dots[i]) dots[i].classList.remove('active');
        }
      });
      currentSlide = index;
    }

    function nextSlide() {
      let next = (currentSlide + 1) % slides.length;
      showSlide(next);
    }

    let autoSlide = setInterval(nextSlide, slideInterval);

    // Click handler for dots
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        clearInterval(autoSlide);
        showSlide(index);
        autoSlide = setInterval(nextSlide, slideInterval);
      });
    });
  });
</script>
@endsection
