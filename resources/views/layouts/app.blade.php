<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Kerala State Lotteries')</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1.0.5">
  
  <!-- Custom View styles -->
  @yield('styles')
</head>
<body>

  <!-- Header -->
  <header>
    <div class="container nav-wrapper">
      <div class="logo">
        <a href="{{ route('home') }}">
          <img src="{{ asset('images/logo.jpeg') }}" alt="Kerala Jackpot" style="max-height: 45px; display: block;">
        </a>
      </div>
      <div class="menu-btn">☰</div>
      <nav class="nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
        <a href="{{ route('buy-tickets') }}" class="{{ request()->routeIs('buy-tickets') ? 'active' : '' }}">Buy Tickets</a>
        <a href="{{ route('results') }}" class="{{ request()->routeIs('results') ? 'active' : '' }}">Results</a>
        <a href="{{ route('track-order') }}" class="{{ request()->routeIs('track-order') ? 'active' : '' }}">Track Order</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  @yield('content')

  <!-- Footer -->
  <footer>
    <div class="container footer-grid">
      <!-- Column 1 -->
      <div class="footer-col brand-col">
        <h3>Directorate of Kerala State Lotteries</h3>
        <div class="footer-address">
          <p>Vikas Bhavan P.O., Thiruvananthapuram</p>
          <p>Kerala – 695033.</p>
          <p>Ph: 8252334861, 8252334861 (Fax)</p>
          <p class="email">e-mail: support@jackpot.keralastateslotterys.com</p>
        </div>
        <div class="social-links">
          <a href="#" class="social-icon">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2.04c-5.5 0-10 4.49-10 10.02 0 5 3.66 9.15 8.44 9.9v-7H7.9v-2.9h2.54V9.85c0-2.51 1.49-3.89 3.78-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.45 2.9h-2.33v7a10 10 0 008.44-9.9c0-5.53-4.5-10.02-10-10.02z"/>
            </svg>
          </a>
          <a href="#" class="social-icon">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
            </svg>
          </a>
          <a href="#" class="social-icon">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Column 2 -->
      <div class="footer-col">
        <h3>Quick Links</h3>
        <ul class="footer-links-list">
          <li><a href="#">Terms and Conditions</a></li>
          <li><a href="#">Copyright Policy</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Hyperlinking Policy</a></li>
        </ul>
      </div>

      <!-- Column 3 -->
      <div class="footer-col">
        <h3>Information</h3>
        <ul class="footer-links-list">
          <li><a href="#">ADS</a></li>
          <li><a href="#">Downloads</a></li>
          <li><a href="#">Feedback</a></li>
          <li><a href="#">Tenders</a></li>
          <li><a href="#">RTI</a></li>
          <li><a href="#">FAQ</a></li>
        </ul>
      </div>

      <!-- Column 4 -->
      <div class="footer-col counter-col">
        <div class="visitors-counter-container">
          <div class="visitor-counter">
            <span>9</span><span>7</span><span>2</span><span>5</span><span>8</span><span>2</span>
          </div>
          <p class="counter-label">Visitors Counter</p>
        </div>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom container">
      <div class="footer-meta">
        <span class="last-modified">Last Modified: Tuesday 22, November 2022.</span>
      </div>
      <p class="copyright">&copy; 2026 Kerala State Lotteries Recreation. All rights reserved.</p>
    </div>
  </footer>

  <!-- Custom JS -->
  <script src="{{ asset('js/main.js') }}"></script>
  
  <!-- Custom View Scripts -->
  @yield('scripts')
</body>
</html>
