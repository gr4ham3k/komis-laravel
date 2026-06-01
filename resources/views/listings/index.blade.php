@extends('layouts.app')

@section('title', 'Ogłoszenia samochodowe - MotoKomis')

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Samochody z bazy</p>
                    <h1 class="h2 mb-1">Ogłoszenia samochodowe</h1>
                    <p class="text-muted mb-0">Filtruj po marce, modelu, parametrach technicznych i tagach wyposażenia.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('listings.create') }}" class="btn btn-mk">
                        <i class="fas fa-plus me-1"></i> Dodaj auto
                    </a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-screwdriver-wrench me-1"></i> Usługi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row g-4">
            <aside class="col-lg-4 col-xl-3">
                <div class="position-sticky" style="top: 88px;">
                    @include('listings._filters', ['selectedTags' => $selectedTags])
                </div>
            </aside>

            <section class="col-lg-8 col-xl-9">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                    <div>
                        <h2 class="h5 mb-1">Znaleziono {{ $listings->total() }} ogłoszeń</h2>
                        <p class="text-muted mb-0 small">Wyniki aktualizują się według wybranych filtrów.</p>
                    </div>
                    @if(request()->query())
                        <a href="{{ route('listings.index') }}" class="btn btn-outline-secondary btn-sm">Usuń filtry</a>
                    @endif
                </div>

                <div class="row g-3">
                    @forelse($listings as $listing)
                        <div class="col-md-6 col-xl-4">
                            @include('listings._card', ['listing' => $listing])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="filter-panel p-5 text-center">
                                <i class="fas fa-car-burst fa-2x text-muted mb-3"></i>
                                <h2 class="h5">Brak wyników dla tych filtrów</h2>
                                <p class="text-muted mb-3">Zmień zakres ceny, rocznika lub usuń część tagów.</p>
                                <a href="{{ route('listings.index') }}" class="btn btn-mk">Pokaż wszystkie ogłoszenia</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $listings->links() }}
                </div>
            </section>
        </div>
    </div>
@endsection
