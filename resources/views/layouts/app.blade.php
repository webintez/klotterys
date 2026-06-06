<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Kerala State Lotteries')</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1.0.4">
  
  <!-- Custom View styles -->
  @yield('styles')
</head>
<body>

  <!-- Header -->
  <header>
    <div class="container nav-wrapper">
      <div class="logo">Kerala<span>Jackpot</span></div>
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
    <div class="container">
      <div class="logo">Kerala<span>Jackpot</span></div>
      <div class="footer-links">
        <a href="{{ route('about') }}">About Us</a>
        <a href="{{ route('contact') }}">Contact Us</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="{{ route('admin.login') }}">Admin Login</a>
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
