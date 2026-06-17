@php
    $compareIds = session('compare_listings', []);
    $isCompared = in_array($listing->id, $compareIds, true);
@endphp

<div class="listing-card d-flex flex-row h-100">
    <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none flex-shrink-0 list-thumb-wrap">
        @if($listing->images->first())
            <img
                src="{{ asset('storage/' . $listing->images->first()->file_name) }}"
                class="list-thumb"
                alt="{{ $listing->title }}"
            >
        @else
            <div class="list-thumb d-flex align-items-center justify-content-center text-muted">
                <i class="fas fa-camera fa-2x"></i>
            </div>
        @endif
    </a>

    <div class="p-3 d-flex flex-column flex-grow-1 min-w-0">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <h3 class="h6 fw-bold mb-0 text-truncate">
                <a href="{{ route('listings.show', $listing) }}" class="text-dark text-decoration-none">
                    {{ $listing->title }}
                </a>
            </h3>
            @if($listing->status === 'active')
                <span class="badge bg-success small ms-2 flex-shrink-0">Aktywne</span>
            @else
                <span class="badge bg-secondary small ms-2 flex-shrink-0">Nieaktywne</span>
            @endif
        </div>

        <p class="text-muted small mb-2">
            {{ $listing->brand?->name }} {{ $listing->carModel?->name }} &bull; {{ $listing->city }}
        </p>

        <div class="d-flex flex-wrap gap-1 mb-2">
            @if($listing->year)
                <span class="spec-chip"><i class="far fa-calendar-alt me-1"></i>{{ $listing->year }}</span>
            @endif
            @if($listing->mileage)
                <span class="spec-chip"><i class="fas fa-tachometer-alt me-1"></i>{{ number_format($listing->mileage, 0, ',', ' ') }} km</span>
            @endif
            @if($listing->fuel?->name)
                <span class="spec-chip"><i class="fas fa-gas-pump me-1"></i>{{ $listing->fuel->name }}</span>
            @endif
            @if($listing->engine_capacity)
                <span class="spec-chip"><i class="fas fa-microchip me-1"></i>{{ $listing->engine_capacity }} cm³</span>
            @endif
            @if($listing->power_hp)
                <span class="spec-chip"><i class="fas fa-horse-head me-1"></i>{{ $listing->power_hp }} KM</span>
            @endif
            @if($listing->transmission?->name)
                <span class="spec-chip"><i class="fas fa-cog me-1"></i>{{ $listing->transmission->name }}</span>
            @endif
        </div>

        <div class="mt-auto d-flex align-items-center justify-content-between gap-2">
            <span class="fs-5 fw-bold text-success">{{ number_format($listing->price, 0, ',', ' ') }} PLN</span>

            @if($isCompared)
                <form method="POST" action="{{ route('compare.destroy', $listing) }}" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        Usuń z porównania
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('compare.store', $listing) }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm">
                        Dodaj do porównania
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
