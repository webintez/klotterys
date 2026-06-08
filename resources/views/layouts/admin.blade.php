<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard') - Kerala Jackpot Admin</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
  
  <!-- Admin Panel CSS -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  
  @yield('styles')
</head>
<body>

  <div class="admin-container">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="sidebar-logo">
        Kerala<span>Jackpot</span>
      </div>
      <ul class="sidebar-menu">
        <li class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}">
            <span style="margin-right: 10px;">📊</span> Dashboard
          </a>
        </li>
        <li class="sidebar-menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
          <a href="{{ route('admin.bookings.index') }}">
            <span style="margin-right: 10px;">🎟️</span> Manage Bookings
          </a>
        </li>
        <li class="sidebar-menu-item {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
          <a href="{{ route('admin.results.index') }}">
            <span style="margin-right: 10px;">🏆</span> Manage Results
          </a>
        </li>
        <li class="sidebar-menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
          <a href="{{ route('admin.settings.edit') }}">
            <span style="margin-right: 10px;">⚙️</span> Website Settings
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ route('home') }}" target="_blank">
            <span style="margin-right: 10px;">🌐</span> View Site
          </a>
        </li>
      </ul>
      
      <div class="sidebar-logout-btn">
        <form action="{{ route('admin.logout') }}" method="POST">
          @csrf
          <button type="submit">Logout</button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-content">
      <!-- Session Messages -->
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul style="margin-left: 1rem;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  @yield('scripts')
</body>
</html>
