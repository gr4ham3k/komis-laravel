@extends('layouts.app')

@section('title', $listing->title . ' - MotoKomis')

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Szczegóły ogłoszenia</p>
                    <h1 class="h2 mb-1">{{ $listing->title }}</h1>
                    <p class="text-muted mb-0">
                        {{ $listing->brand?->name }} {{ $listing->carModel?->name }} · {{ $listing->city }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('listings.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-1"></i> Wszystkie ogłoszenia
                    </a>
                    <a href="{{ route('listings.create') }}" class="btn btn-mk">
                        <i class="fas fa-plus me-1"></i> Dodaj auto
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row g-4">
            <div class="col-lg-8">
                @if ($listing->images->isNotEmpty())
                    <div id="carouselExample" class="carousel slide mb-3">
                        <div class="carousel-inner">
                            @foreach ($listing->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->file_name) }}" class="d-block w-100 rounded"
                                        style="height: 450px; object-fit: cover;" alt="{{ $listing->title }}">
                                </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                @else
                    <div class="filter-panel p-4 mb-3 text-center">
                        <i class="fas fa-image fa-2x text-muted mb-3"></i>
                        <h2 class="h5">Brak zdjęć ogłoszenia</h2>
                        <a href="{{ route('listings.images.create', $listing->id) }}" class="btn btn-mk mt-2">
                            <i class="fas fa-plus me-1"></i> Dodaj zdjęcia
                        </a>
                    </div>
                @endif

                @if ($listing->tags->isNotEmpty())
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        @foreach ($listing->tags as $tag)
                            <span class="badge bg-dark">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="filter-panel p-4 mb-3">
                    <h2 class="h5">Opis</h2>
                    <p class="mb-0">{{ $listing->description }}</p>
                </div>

                <div class="filter-panel p-4">
                    <h2 class="h5">Specyfikacja</h2>

                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-car-side me-1"></i> <strong>Marka:</strong> {{ $listing->brand->name }}</p>
                            <p><i class="fas fa-car me-1"></i> <strong>Model:</strong> {{ $listing->carModel->name }}</p>
                            <p><i class="fas fa-table-cells-large me-1"></i> <strong>Nadwozie:</strong>
                                {{ $listing->bodyType->name }}</p>
                            <p><i class="fas fa-location-dot me-1"></i> <strong>Miasto:</strong> {{ $listing->city }}</p>
                            <p><i class="fas fa-calendar me-1"></i> <strong>Rok:</strong> {{ $listing->year }}</p>
                            <p><i class="fas fa-road me-1"></i> <strong>Przebieg:</strong> {{ $listing->mileage }} km</p>
                        </div>

                        <div class="col-md-6">
                            <p><i class="fas fa-gears me-1"></i> <strong>Skrzynia biegów:</strong>
                                {{ $listing->transmission->name }}</p>
                            <p><i class="fas fa-gauge-high me-1"></i> <strong>Moc:</strong> {{ $listing->power_hp }} KM</p>
                            <p><i class="fas fa-wrench me-1"></i> <strong>Pojemność silnika:</strong>
                                {{ $listing->engine_capacity }} cm³</p>
                            <p><i class="fas fa-palette me-1"></i> <strong>Kolor:</strong> {{ $listing->color }}</p>
                            <p><i class="fas fa-gas-pump me-1"></i> <strong>Paliwo:</strong> {{ $listing->fuel->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="filter-panel p-4 mt-3">
                    <h2 class="h5">Lokalizacja</h2>
                    <div id="map" style="height: 300px; border-radius: 8px;"></div>
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="filter-panel p-4">
                    <p>
                        Status:
                        @if ($listing->status === 'active')
                            <span class="badge bg-success">Aktywne</span>
                        @else
                            <span class="badge bg-secondary">Nieaktywne</span>
                        @endif
                    </p>

                    <h2 class="h3 mb-1">{{ number_format($listing->price, 2) }} PLN</h2>
                    <hr>
                    <p><strong>Sprzedający:</strong> {{ $listing->user->name }}</p>

                    @auth
                        @if (auth()->id() === $listing->user_id)
                            <a href="{{ route('listings.edit', $listing) }}" class="btn btn-mk w-100">
                                <i class="fas fa-pen me-1"></i> Edytuj ogłoszenie
                            </a>
                        @else
                            <form method="get" action="{{ route('conversations.start', $listing) }}">
                                <button class="btn btn-mk w-100">
                                    <i class="fas fa-envelope me-1"></i> Napisz do sprzedającego
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </aside>
        </div>
    </div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView(
            [{{ $listing->latitude }}, {{ $listing->longitude }}],
            12
        );

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([{{ $listing->latitude }}, {{ $listing->longitude }}])
            .addTo(map)
            .bindPopup("{{ $listing->city }}")
            .openPopup();
    });
</script>
@endpush
@endsection
