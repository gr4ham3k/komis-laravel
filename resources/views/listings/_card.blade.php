@php
    $compareIds = session('compare_listings', []);
    $isCompared = in_array($listing->id, $compareIds, true);
@endphp

<div class="listing-card d-flex flex-column h-100">
    <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none">
        @if($listing->images->first())
            <img
                src="{{ asset('storage/listings/' . $listing->images->first()->file_name) }}"
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
