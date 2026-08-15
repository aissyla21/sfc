<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SFC - Semarang Fencing Club</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="{{ url('/') }}" class="navbar-brand">
            <div class="logo-icon"></div>
            SFC
        </a>
        <div class="navbar-links">
            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="btn-primary">Join SFC</a>
            @else
                <a href="{{ Auth::user()->role === 'pelatih' ? route('pelatih.dashboard') : route('dashboard') }}">
                    Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:var(--text-muted); font-weight:700; cursor:pointer; margin-left:20px;">
                        Logout
                    </button>
                </form>
            @endguest
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    @stack('scripts')
</body>
</html>
