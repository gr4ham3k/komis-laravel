@extends('layouts.app')

@section('title', 'MotoKomis - samochody i usługi motoryzacyjne')

@push('styles')
    <style>
        .home-hero {
            position: relative;
            min-height: 620px;
            display: flex;
            align-items: center;
            background:
                linear-gradient(90deg, rgba(12, 18, 28, .90), rgba(12, 18, 28, .60), rgba(12, 18, 28, .28)),
                url('{{ asset('images/bmw2.jpg') }}') center / cover no-repeat;
            color: #fff;
        }

        .home-hero::after {
            content: "";
            position: absolute;
            inset: auto 0 0;
            height: 90px;
            background: linear-gradient(180deg, rgba(244, 246, 248, 0), #f4f6f8);
        }

        .home-hero .container {
            position: relative;
            z-index: 1;
        }

        .hero-copy {
            max-width: 680px;
        }

        .hero-copy h1 {
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: 1;
            letter-spacing: 0;
        }

        .home-search {
            color: var(--mk-ink);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .24);
        }
    </style>
@endpush

@section('content')
    <section class="home-hero">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="hero-copy">
                        <p class="text-uppercase fw-semibold text-white-50 mb-2">Komis samochodowy</p>
                        <h1 class="fw-bold mb-3">MotoKomis</h1>
                        <p class="fs-5 text-white-75 mb-4">
                            Znajdź sprawdzone auta, porównaj najnowsze ogłoszenia i skorzystaj z usług motoryzacyjnych w jednym miejscu.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('listings.index') }}" class="btn btn-mk btn-lg">
                                <i class="fas fa-car-side me-1"></i> Zobacz ogłoszenia
                            </a>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-screwdriver-wrench me-1"></i> Usługi motoryzacyjne
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="home-search filter-panel p-4">
                        <p class="text-uppercase text-muted fw-semibold small mb-1">Szybkie wyszukiwanie</p>
                        <h2 class="h4 mb-3">Czego szukasz?</h2>
                        <form method="GET" action="{{ route('listings.index') }}" class="d-flex flex-column gap-3">
                            <div>
                                <label for="homeSearch" class="form-label">Wpisz markę, model lub miasto</label>
                                <input
                                    type="search"
                                    name="q"
                                    id="homeSearch"
                                    class="form-control form-control-lg"
                                    placeholder="np. BMW, Audi, Kraków"
                                >
                            </div>
                            <button type="submit" class="btn btn-mk btn-lg">
                                <i class="fas fa-search me-1"></i> Szukaj ogłoszeń
                            </button>
                            <a href="{{ route('listings.index') }}" class="btn btn-outline-dark">
                                Pokaż wszystkie ogłoszenia
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mt-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="metric-tile p-3">
                    <span class="text-muted small">Aktywne ogłoszenia</span>
                    <div class="h3 mb-0">{{ $stats['listings'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-tile p-3">
                    <span class="text-muted small">Marki w bazie</span>
                    <div class="h3 mb-0">{{ $stats['brands'] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-tile p-3">
                    <span class="text-muted small">Aktywne usługi</span>
                    <div class="h3 mb-0">{{ $stats['services'] }}</div>
                </div>
            </div>
        </div>
    </section>

    @if($popularTags->isNotEmpty())
        <section class="container my-5">
            <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Szybkie tagi</p>
                    <h2 class="h4 mb-0">Najczęściej wybierane cechy</h2>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($popularTags as $tag)
                    <a class="tag-chip" href="{{ route('listings.index', ['tags' => [$tag->id]]) }}">
                        #{{ $tag->name }}
                        <span class="text-muted">{{ $tag->listings_count }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section-band py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Najnowsze z komisu</p>
                    <h2 class="h3 mb-0">Polecane ogłoszenia</h2>
                </div>
                <a href="{{ route('listings.index') }}" class="btn btn-outline-dark">Wszystkie ogłoszenia</a>
            </div>

            <div class="row g-3">
                @forelse($featuredListings as $listing)
                    <div class="col-md-6 col-xl-4">
                        @include('listings._card', ['listing' => $listing])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="filter-panel p-4">
                            <h3 class="h5">Nie ma jeszcze aktywnych ogłoszeń</h3>
                            <p class="text-muted mb-3">Dodaj pierwsze auto i uzupełnij parametry, żeby zaczęło pojawiać się w filtrach.</p>
                            <a href="{{ route('listings.create') }}" class="btn btn-mk">Dodaj ogłoszenie</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
