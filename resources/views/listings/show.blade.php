@extends('layouts.site')

@section('title', $listing->title)

@section('content')
    @php
        $resolveImagePath = function ($fileName) {
            if (! $fileName) {
                return 'https://via.placeholder.com/800x420?text=Brak+zdjecia';
            }

            if (str_starts_with($fileName, 'http://') || str_starts_with($fileName, 'https://')) {
                return $fileName;
            }

            if (file_exists(public_path('images/' . $fileName))) {
                return asset('images/' . $fileName);
            }

            return asset('storage/listings/' . $fileName);
        };
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">
            <div id="listingCarousel" class="carousel slide mb-3 bg-white border rounded shadow-sm overflow-hidden">
                <div class="carousel-inner">
                    @forelse ($listing->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $resolveImagePath($image->file_name) }}" class="d-block w-100" style="height: 420px; object-fit: cover;" alt="{{ $image->original_name }}">
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/800x420?text=Brak+zdjecia" class="d-block w-100" style="height: 420px; object-fit: cover;" alt="Brak zdjecia">
                        </div>
                    @endforelse
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h5">Opis</h2>
                    <p class="mb-0">{{ $listing->description }}</p>
                    @if ($listing->tags->isNotEmpty())
                        <div class="mt-3">
                            @foreach ($listing->tags as $tag)
                                <span class="badge text-bg-secondary me-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5">Specyfikacja</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Marka:</strong> {{ optional($listing->brand)->name ?? 'Brak danych' }}</p>
                            <p class="mb-2"><strong>Model:</strong> {{ optional($listing->carModel)->name ?? 'Brak danych' }}</p>
                            <p class="mb-2"><strong>Miasto:</strong> {{ $listing->city }}</p>
                            <p class="mb-2"><strong>Rok:</strong> {{ $listing->year }}</p>
                            <p class="mb-2"><strong>Przebieg:</strong> {{ number_format($listing->mileage, 0, ',', ' ') }} km</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Paliwo:</strong> {{ optional($listing->fuel)->name ?? 'Brak danych' }}</p>
                            <p class="mb-2"><strong>Skrzynia:</strong> {{ optional($listing->transmission)->name ?? 'Brak danych' }}</p>
                            <p class="mb-2"><strong>Kolor:</strong> {{ $listing->color }}</p>
                            <p class="mb-2"><strong>Pojemnosc:</strong> {{ $listing->engine_capacity }} cm3</p>
                            <p class="mb-2"><strong>Moc:</strong> {{ $listing->power_hp }} KM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-2">{{ $listing->title }}</h1>
                    <p class="fs-3 fw-semibold text-success mb-3">{{ number_format((float) $listing->price, 0, ',', ' ') }} PLN</p>
                    <p class="mb-2"><strong>Sprzedawca:</strong> {{ optional($listing->user)->name ?? 'Brak danych' }}</p>
                    <p class="mb-2"><strong>Wyswietlenia:</strong> {{ $listing->views_count }}</p>
                    <p class="text-secondary small mb-3">Dodano: {{ $listing->created_at->format('Y-m-d') }}</p>
                    <button class="btn btn-primary w-100" type="button">Napisz do sprzedawcy</button>
                </div>
            </div>
        </div>
    </div>
@endsection
