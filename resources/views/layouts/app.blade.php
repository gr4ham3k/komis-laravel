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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        :root {
            --mk-ink: #15202b;
            --mk-muted: #657080;
            --mk-line: #dfe4ea;
            --mk-soft: #f4f6f8;
            --mk-accent: #e63946;
            --mk-accent-dark: #c92d39;
            --mk-deep: #111827;
        }

        html, body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--mk-soft);
            color: var(--mk-ink);
        }

        .navbar {
            box-shadow: 0 8px 28px rgba(17, 24, 39, .12);
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-grid;
            place-items: center;
            background: var(--mk-accent);
            color: #fff;
            margin-right: .45rem;
        }

        .btn-mk {
            --bs-btn-bg: var(--mk-accent);
            --bs-btn-border-color: var(--mk-accent);
            --bs-btn-hover-bg: var(--mk-accent-dark);
            --bs-btn-hover-border-color: var(--mk-accent-dark);
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
        }

        .filter-panel,
        .listing-card,
        .metric-tile {
            border: 1px solid var(--mk-line);
            border-radius: 8px;
            background: #fff;
        }

        .listing-card {
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .listing-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(17, 24, 39, .10);
        }

        .listing-thumb {
            width: 100%;
            max-width: 100%;
            display: block;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            background: #e9ecef;
        }

        .spec-chip {
            display: inline-flex;
            align-items: center;
            font-size: .75rem;
            background: var(--mk-soft);
            border: 1px solid var(--mk-line);
            border-radius: 6px;
            padding: .2rem .55rem;
            color: var(--mk-muted);
            white-space: nowrap;
        }

        .list-thumb-wrap {
            width: 260px;
            min-height: 180px;
        }

        .list-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 576px) {
            .list-thumb-wrap {
                width: 140px;
                min-height: 120px;
            }
            .listing-card.flex-row {
                flex-wrap: wrap;
            }
            .listing-card.flex-row .list-thumb-wrap {
                width: 100%;
                min-height: 180px;
            }
        }

        .tag-chip {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            border: 1px solid var(--mk-line);
            border-radius: 999px;
            padding: .35rem .65rem;
            background: #fff;
            color: var(--mk-ink);
            font-size: .85rem;
            text-decoration: none;
        }

        .tag-chip:hover {
            border-color: var(--mk-accent);
            color: var(--mk-accent);
        }

        .section-band {
            background: #fff;
            border-top: 1px solid var(--mk-line);
            border-bottom: 1px solid var(--mk-line);
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-semibold d-flex align-items-center" href="{{ route('home') }}">
                <span class="brand-mark"><i class="fas fa-car-side"></i></span>
                MotoKomis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Strona główna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('listings.*') ? 'active' : '' }}" href="{{ route('listings.index') }}">Ogłoszenia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Usługi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('compare.*') ? 'active' : '' }}" href="{{ route('compare.index') }}">
                            Porównaj
                            @if (session('compare_listings'))
                                <span class="badge rounded-pill text-bg-light text-dark ms-1">{{ count(session('compare_listings')) }}</span>
                            @endif
                        </a>
                    </li>
                    @auth
                        @if (Auth::user()->is_admin)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                    Panel admina
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ request()->routeIs('admin.listings.*') ? 'active' : '' }}" href="{{ route('admin.listings.index') }}">Ogłoszenia</a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">Usługi</a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Użytkownicy</a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('admin.dictionaries.*') ? 'active' : '' }}" href="{{ route('admin.dictionaries.index') }}">Słowniki</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <li class="nav-item d-none d-lg-block">
                        <a class="btn btn-mk btn-sm px-3" href="{{ route('listings.create') }}">
                            <i class="fas fa-plus me-1"></i> Dodaj ogłoszenie
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="{{ route('listings.create') }}">Dodaj ogłoszenie</a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('user.panel') }}">Panel użytkownika</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">Mój profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('conversations.index') }}">Wiadomości</a></li>
                                <li><a class="dropdown-item" href="{{ route('my.listings') }}">Moje ogłoszenia</a></li>
                                <li><a class="dropdown-item" href="{{ route('my.services') }}">Moje usługi</a></li>
                                <li><hr class="dropdown-divider"></li>
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
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Logowanie</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Rejestracja</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <main class="py-0">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} MotoKomis - Wszystkie prawa zastrzeżone</span>
            <span class="text-white-50">Ogłoszenia i usługi motoryzacyjne w jednym miejscu</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @stack('scripts')
</body>
</html>
