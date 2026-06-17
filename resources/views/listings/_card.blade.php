@php
    $compareIds = session('compare_listings', []);
    $isCompared = in_array($listing->id, $compareIds, true);
@endphp

<div class="listing-card d-flex flex-column h-100">
    <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none d-block">
        @if($listing->images->first())
            <img
                src="{{ asset('storage/' . $listing->images->first()->file_name) }}"
                class="listing-thumb"
                alt="{{ $listing->title }}"
            >
        @else
            <div class="listing-thumb d-flex align-items-center justify-content-center text-muted">
                <i class="fas fa-camera fa-2x"></i>
            </div>
        @endif
    </a>

    <div class="p-3 d-flex flex-column flex-grow-1">
        <div class="mb-2 d-flex justify-content-between align-items-start">
            <h3 class="h6 fw-bold mb-0 text-truncate" style="max-width: 80%;">
                <a href="{{ route('listings.show', $listing) }}" class="text-dark text-decoration-none">
                    {{ $listing->title }}
                </a>
            </h3>
            @if($listing->status === 'active')
                <span class="badge bg-success small">Aktywne</span>
            @else
                <span class="badge bg-secondary small">Nieaktywne</span>
            @endif
        </div>

        <p class="text-muted small mb-2 text-truncate">
            {{ $listing->brand?->name }} {{ $listing->carModel?->name }} &bull; {{ $listing->city }}
        </p>

        <div class="d-flex flex-wrap gap-1 mb-3 spec-chips">
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

        <div class="mt-auto">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="fs-5 fw-bold text-success">{{ number_format($listing->price, 0, ',', ' ') }} PLN</span>
            </div>

            <div class="d-grid gap-2">
                @if($isCompared)
                    <form method="POST" action="{{ route('compare.destroy', $listing) }}" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            Usuń z porównania
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('compare.store', $listing) }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark btn-sm w-100">
                            Dodaj do porównania
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
