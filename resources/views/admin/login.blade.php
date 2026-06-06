<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Kerala Jackpot</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
  
  <!-- Admin Panel CSS -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

  <div class="auth-wrapper">
    <div class="auth-card">
      <div class="auth-header">
        <div class="auth-logo">Kerala<span>Jackpot</span></div>
        <div class="auth-title">Administrator Portal</div>
      </div>

      <!-- Alerts -->
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
          <ul style="margin-left: 1rem; list-style-type: disc;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="admin@keralajackpot.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group font-main">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-admin" style="margin-top: 2rem;">Sign In</button>
      </form>
    </div>
  </div>

</body>
</html>
