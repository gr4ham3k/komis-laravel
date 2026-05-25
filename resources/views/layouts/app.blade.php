<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Komis Samochodowy</title>

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
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-car-side"></i> Komis Samochodowy
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Strona główna</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('listings.index') }}">Ogłoszenia</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Usługi</a></li>
                @auth
                    @if(Auth::user()->is_admin && Route::has('admin.users.index'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Panel admina</a></li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item d-flex align-items-center text-white me-2">
                        <span class="small"><i class="fas fa-user"></i> {{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link p-0">Wyloguj</button>
                        </form>
                    </li>
                @else
                    @if(Route::has('login'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Logowanie</a></li>
                    @endif
                    @if(Route::has('register'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Rejestracja</a></li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>

@if(session('success') || session('error') || $errors->any())
    <div class="container pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif

<main>
    @yield('content')
</main>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
        <small>&copy; {{ date('Y') }} Komis Samochodowy</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
