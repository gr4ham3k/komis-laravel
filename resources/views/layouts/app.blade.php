<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MotoKomis') }}</title>
    <link rel="icon" href="data:,">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #f6f7fb 0%, #eef2f7 100%);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .nav-link.active {
            color: #ffffff;
            font-weight: 600;
            position: relative;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0.5rem;
            right: 0.5rem;
            bottom: 0.2rem;
            height: 2px;
            border-radius: 999px;
            background: #ffffff;
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-car"></i> MotoKomis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"
                            @if (request()->routeIs('home')) aria-current="page" @endif>Strona glowna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('listings.*') ? 'active' : '' }}"
                            href="{{ route('listings.index') }}"
                            @if (request()->routeIs('listings.*')) aria-current="page" @endif>Ogloszenia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
                            href="{{ route('services.index') }}"
                            @if (request()->routeIs('services.*')) aria-current="page" @endif>Uslugi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('compare.*') ? 'active' : '' }}"
                            href="{{ route('compare.index') }}"
                            @if (request()->routeIs('compare.*')) aria-current="page" @endif>
                            Porownaj
                            @if (session('compare_listings'))
                                <span
                                    class="badge rounded-pill text-bg-light text-dark ms-1">{{ count(session('compare_listings')) }}</span>
                            @endif
                        </a>
                    </li>
                    @auth
                        @if (Auth::user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                                    href="{{ route('admin.services.index') }}"
                                    @if (request()->routeIs('admin.*')) aria-current="page" @endif>Panel admina</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('listings.create') }}">Dodaj ogloszenie</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('conversations.index') }}">Wiadomosci</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Wyloguj</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                href="{{ route('login') }}"
                                @if (request()->routeIs('login')) aria-current="page" @endif>Logowanie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                                href="{{ route('register') }}"
                                @if (request()->routeIs('register')) aria-current="page" @endif>Rejestracja</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} MotoKomis - Wszystkie prawa zastrzezone</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')  <!-- ← DODAJ TO - to jest kluczowe! -->
</body>
</html>