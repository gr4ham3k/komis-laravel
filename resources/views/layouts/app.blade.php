<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Komis Samochodowy + Usługi Motoryzacyjne</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-car"></i> MotoKomis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Strona główna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('listings.index') }}">Ogłoszenia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.index') }}">Usługi</a>
                    </li>
                    @auth
                        @if(Auth::user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.services.index') }}">Admin usługi</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Mój profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('my.listings') }}">Moje ogłoszenia</a></li>
                                <li><a class="dropdown-item" href="{{ route('my.services') }}">Moje usługi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @if(Route::has('logout'))
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Wyloguj</button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @else
                        @if(Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Logowanie</a>
                            </li>
                        @endif
                        @if(Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Rejestracja</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} MotoKomis - Wszystkie prawa zastrzeżone</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
