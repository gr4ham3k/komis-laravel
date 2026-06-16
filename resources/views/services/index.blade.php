
@extends('layouts.app')

@section('content')

    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Usługi motoryzacyjne</h1>
                <p class="text-muted mb-0">Znajdź warsztat, diagnostę albo pomoc przy samochodzie.</p>
            </div>

            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('services.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Dodaj usługę
                    </a>
                @endauth
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Powrót</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card shadow-sm border-0" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-filter me-2"></i>Filtry
                        </h5>

                        <form method="GET" action="{{ route('services.index') }}" id="filterForm">
                            <!-- Ukryte pole search aby zachować wyszukiwanie -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <!-- FILTR: Miasto -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-city me-1"></i>Miasto
                                </label>
                                <select name="city" class="form-select">
                                    <option value="">Wszystkie miasta</option>
                                    @foreach($cities ?? [] as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- FILTR: Zakres cenowy -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-dollar-sign me-1"></i>Zakres cenowy (PLN)
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number"
                                               name="price_min"
                                               class="form-control"
                                               placeholder="Od"
                                               value="{{ request('price_min') }}"
                                               min="0"
                                               step="1">
                                    </div>
                                    <div class="col-6">
                                        <input type="number"
                                               name="price_max"
                                               class="form-control"
                                               placeholder="Do"
                                               value="{{ request('price_max') }}"
                                               min="0"
                                               step="1">
                                    </div>
                                </div>
                            </div>

                            <!-- FILTR: Sortowanie -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-sort me-1"></i>Sortuj według
                                </label>
                                <select name="sort" class="form-select">
                                    <option value="">Najnowsze</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        Cena: od najniższej
                                    </option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Cena: od najwyższej
                                    </option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                        Najstarsze
                                    </option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                        Najpopularniejsze (wyświetlenia)
                                    </option>
                                    <option value="best_rated" {{ request('sort') == 'best_rated' ? 'selected' : '' }}>
                                        Najwyżej oceniane
                                    </option>
                                </select>
                            </div>

                            <!-- Przyciski akcji -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Filtruj
                                </button>
                                <a href="{{ route('services.index') }}@if(request('search'))?search={{ request('search') }}@endif"
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i>Resetuj filtry
                                </a>
                            </div>
                        </form>

                        <!-- Aktywne filtry - podsumowanie -->
                        @if(request()->anyFilled(['city', 'price_min', 'price_max', 'sort']))
                            <hr>
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Aktywne filtry:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @if(request('city'))
                                        <span class="badge bg-secondary">
                                            Miasto: {{ request('city') }}
                                        </span>
                                    @endif
                                    @if(request('price_min') || request('price_max'))
                                        <span class="badge bg-secondary">
                                            Cena: {{ request('price_min') ?: '0' }} - {{ request('price_max') ?: '∞' }} PLN
                                        </span>
                                    @endif
                                    @if(request('sort'))
                                        <span class="badge bg-secondary">
                                            Sortowanie:
                                            @switch(request('sort'))
                                                @case('price_asc') Od najniższej @break
                                                @case('price_desc') Od najwyższej @break
                                                @case('oldest') Najstarsze @break
                                                @case('popular') Najpopularniejsze @break
                                                @case('best_rated') Najwyżej oceniane @break
                                                @default {{ request('sort') }}
                                            @endswitch
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- LISTA USŁUG - PRAWA STRONA -->
            <div class="col-lg-9">
                <!-- Formularz wyszukiwania (uproszczony, bo filtry są obok) -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('services.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-9">
                                    <label for="search" class="form-label fw-semibold">Czego szukasz?</label>
                                    <input
                                        type="search"
                                        id="search"
                                        name="search"
                                        value="{{ $search ?? '' }}"
                                        class="form-control"
                                        placeholder="Nazwa usługi, opis albo miasto"
                                    >
                                </div>
                                <div class="col-md-3 d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Szukaj
                                    </button>
                                </div>
                            </div>

                            <!-- Przekazanie istniejących filtrów -->
                            @if(request('city'))
                                <input type="hidden" name="city" value="{{ request('city') }}">
                            @endif
                            @if(request('price_min'))
                                <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                            @endif
                            @if(request('price_max'))
                                <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif

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

                <!-- Licznik wyników -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">
                        <i class="fas fa-chart-simple me-1"></i>
                        Znaleziono: <strong>{{ $services->total() }}</strong> usług
                    </p>
                    @if(request()->anyFilled(['city', 'price_min', 'price_max', 'sort']))
                        <small class="text-success">
                            <i class="fas fa-filter me-1"></i>Filtry aktywne
                        </small>
                    @endif
                </div>

                <!-- Lista usług -->
                <div class="row g-4">
                    @forelse ($services as $service)
                        @php
                            $avgRating = $service->averageRating();
                            // $isCompared = in_array($service->id, $compareServiceIds, true);
                        @endphp

                        <div class="col-md-6 col-xl-4">
                            <div class="listing-card d-flex flex-column h-100">
                                <!-- Zdjęcie usługi -->
                                @if($service->images->first())
                                    <img
                                        src="{{ asset('storage/' . $service->images->first()->file_name) }}"
                                        class="listing-thumb"
                                        alt="{{ $service->title }}"
                                    >
                                @else
                                    <div class="listing-thumb d-flex align-items-center justify-content-center text-muted">
                                        @if($service->icon ?? false)
                                            <i class="{{ $service->icon }} fa-4x text-secondary"></i>
                                        @else
                                            <i class="fas fa-screwdriver-wrench fa-4x text-secondary"></i>
                                        @endif
                                    </div>
                                @endif

                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <div class="mb-2">
                                        <span class="badge text-bg-info">Usługa</span>
                                    </div>

                                    <h5 class="card-title">{{ $service->title }}</h5>
                                    <p class="card-text text-muted mb-2">
                                        {{ Str::limit($service->description, 80) }}
                                    </p>
                                    <p class="card-text small text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $service->city }}
                                    </p>

                                    <!-- Ocena -->
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                                <i class="fas fa-star text-warning fa-sm"></i>
                                            @else
                                                <i class="far fa-star text-warning fa-sm"></i>
                                            @endif
                                        @endfor
                                        <span class="text-muted ms-1 small">({{ $service->reviews->count() }})</span>
                                    </div>

                                    <p class="fw-bold text-success mb-3">{{ number_format($service->price, 2) }} PLN</p>

                                    <div class="mt-auto d-grid gap-2">
                                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-primary">
                                            Zobacz szczegóły
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                @if(!empty($search) || request()->anyFilled(['city', 'price_min', 'price_max']))
                                    Brak usług pasujących do kryteriów wyszukiwania.
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
        </div>
    </div>
@endsection
