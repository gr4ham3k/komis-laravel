{{-- resources/views/services/index.blade.php --}}
@extends('layouts.app')

@section('content')
    @php
        // Możesz dodać podobną logikę sesji dla usług jeśli potrzebujesz porównywania
        // $compareServiceIds = session('compare_services', []);
    @endphp

    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Usługi motoryzacyjne</h1>
                <p class="text-muted mb-0">Znajdź warsztat, diagnostę albo pomoc przy samochodzie.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('services.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Dodaj usługę
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Powrót</a>
            </div>
        </div>

        <!-- Formularz wyszukiwania -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('services.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-10">
                            <label for="search" class="form-label fw-semibold">Czego szukasz?</label>
                            <input
                                type="search"
                                id="search"
                                name="search"
                                value="{{ $search ?? '' }}"
                                class="form-control form-control-lg"
                                placeholder="Nazwa usługi, opis albo miasto"
                            >
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-1"></i>Szukaj
                            </button>
                        </div>
                    </div>

                    @if(!empty($search))
                        <div class="mt-3">
                            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary btn-sm">
                                Wyczyść wyszukiwanie
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Lista usług -->
        <div class="row g-4">
            @forelse ($services as $service)
                @php
                    $avgRating = $service->averageRating();
                    // $isCompared = in_array($service->id, $compareServiceIds, true);
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Ikona/obrazek usługi -->
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                            @if($service->icon ?? false)
                                <i class="{{ $service->icon }} fa-4x text-secondary"></i>
                            @else
                                <i class="fas fa-screwdriver-wrench fa-4x text-secondary"></i>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge text-bg-info">Usługa</span>
                            </div>

                            <h5 class="card-title">{{ $service->title }}</h5>
                            <p class="card-text text-muted mb-2">
                                {{ Str::limit($service->description, 100) }}
                            </p>
                            <p class="card-text small text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $service->city }}
                            </p>

                            <!-- Ocena -->
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($avgRating))
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="text-muted ms-1 small">({{ $service->reviews->count() }})</span>
                            </div>

                            <p class="fw-bold text-success mb-3">{{ number_format($service->price, 2) }} PLN</p>

                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-primary">
                                    Zobacz szczegóły
                                </a>

                                <!-- Przykład przycisku do porównywania - odkomentuj jeśli potrzebujesz -->
                                {{--
                                @if($isCompared)
                                    <form method="POST" action="{{ route('services.compare.destroy', $service) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            Usuń z porównania
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('services.compare.store', $service) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark btn-sm w-100">
                                            Dodaj do porównania
                                        </button>
                                    </form>
                                @endif
                                --}}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        @if(!empty($search))
                            Brak usług pasujących do wyszukiwania.
                        @else
                            Brak dostępnych usług.
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginacja -->
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
@endsection