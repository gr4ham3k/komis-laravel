@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="badge text-bg-dark px-3 py-2 mb-3">MotoKomis</div>
                <h1 class="display-4 fw-bold mb-3">Kupuj, sprzedawaj i zarzadzaj ogloszeniami w jednym miejscu.</h1>
                <p class="lead text-muted mb-4">
                    Przegladaj oferty, dodawaj swoje ogloszenia i korzystaj z serwisu po zalogowaniu.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('listings.index') }}" class="btn btn-primary btn-lg">Przegladaj ogloszenia</a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary btn-lg">Uslugi</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        @auth
                            <p class="text-uppercase text-success fw-semibold mb-2">Zalogowano</p>
                            <h2 class="h4 mb-3">Witaj, {{ Auth::user()->name }}</h2>
                            <p class="text-muted mb-4">
                                Masz teraz dostep do dodawania ogloszen, wiadomosci i panelu dopasowanego do Twojego konta.
                            </p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('listings.create') }}" class="btn btn-primary">Dodaj ogloszenie</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">Wyloguj</button>
                                </form>
                            </div>
                        @else
                            <p class="text-uppercase text-muted fw-semibold mb-2">Guest access</p>
                            <h2 class="h4 mb-3">Zaloguj sie, aby odblokowac pelny dostep.</h2>
                            <p class="text-muted mb-4">
                                Rejestracja zajmuje chwile, a po logowaniu wracasz tutaj jako uzytkownik.
                            </p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary">Logowanie</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Rejestracja</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
