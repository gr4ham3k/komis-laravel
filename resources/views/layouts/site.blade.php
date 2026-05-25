<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Komis')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('home') }}"><i class="fas fa-car-side"></i> Komis Samochodowy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Strona glowna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('listings.index') ? 'active' : '' }}" href="{{ route('listings.index') }}">Ogloszenia</a>
                    </li>
                    @if (Route::has('services.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Uslugi</a>
                        </li>
                    @endif
                    @auth
                        @if (auth()->user()->is_admin && Route::has('admin.users.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Panel admina</a>
                            </li>
                        @endif
                        @if (Route::has('logout'))
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button class="nav-link btn btn-link" type="submit">Wyloguj</button>
                                </form>
                            </li>
                        @endif
                    @else
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Logowanie</a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Rejestracja</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="pb-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
