{{-- resources/views/services/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Usługi motoryzacyjne - MotoKomis')

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Serwisy i fachowcy</p>
                    <h1 class="h2 mb-1">Usługi motoryzacyjne</h1>
                    <p class="text-muted mb-0">Przeglądaj dostępne usługi i znajdź wsparcie przy swoim samochodzie.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('services.create') }}" class="btn btn-mk">
                        <i class="fas fa-plus me-1"></i> Dodaj usługę
                    </a>
                    <a href="{{ route('listings.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-car-side me-1"></i> Ogłoszenia
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row g-3">
            @forelse($services as $service)
                <div class="col-md-6 col-xl-4">
                    <article class="listing-card h-100">
                        <div class="p-3 d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <h2 class="h5 card-title mb-0">{{ $service->title }}</h2>
                                <span class="badge text-bg-success">{{ number_format($service->price, 2) }} PLN</span>
                            </div>

                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $service->city }}
                            </p>

                            <p class="card-text flex-grow-1">{{ Str::limit($service->description, 100) }}</p>

                            <div class="mb-3">
                                @php $avgRating = $service->averageRating(); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($avgRating))
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="small text-muted">({{ $service->reviews->count() }})</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-2 mt-auto">
                                <small class="text-muted">Dodano: {{ $service->created_at->format('d.m.Y') }}</small>
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-mk btn-sm">
                                    Zobacz więcej
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="filter-panel p-5 text-center">
                        <i class="fas fa-screwdriver-wrench fa-2x text-muted mb-3"></i>
                        <h2 class="h5">Brak dostępnych usług</h2>
                        <p class="text-muted mb-3">Dodaj pierwszą usługę albo wróć do ogłoszeń samochodowych.</p>
                        <a href="{{ route('services.create') }}" class="btn btn-mk">Dodaj usługę</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
@endsection
