@extends('layouts.app')

@section('content')
    @php
        $compareIds = session('compare_listings', []);
    @endphp

    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Ogloszenia</h1>
                <p class="text-muted mb-0">Przegladaj aktywne oferty dodane przez uzytkownikow.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('compare.index') }}" class="btn btn-outline-dark">
                    Porownaj
                    @if(!empty($compareIds))
                        <span class="badge text-bg-dark ms-1">{{ count($compareIds) }}</span>
                    @endif
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Powrot</a>
            </div>
        </div>

        <div class="row g-4">
            @forelse ($listings as $listing)
                @php
                    $isCompared = in_array($listing->id, $compareIds, true);
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($listing->images->first())
                            <img
                                src="{{ asset('storage/listings/' . $listing->images->first()->file_name) }}"
                                class="card-img-top"
                                style="height: 220px; object-fit: cover;"
                                alt="{{ $listing->title }}"
                            >
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                <span class="text-muted">Brak zdjecia</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                @if($listing->status === 'active')
                                    <span class="badge text-bg-success">Aktywne</span>
                                @else
                                    <span class="badge text-bg-secondary">Nieaktywne</span>
                                @endif
                            </div>

                            <h5 class="card-title">{{ $listing->title }}</h5>
                            <p class="card-text text-muted mb-2">
                                {{ $listing->brand?->name }} {{ $listing->carModel?->name }} | {{ $listing->city }}
                            </p>
                            <p class="fw-bold text-success mb-3">{{ number_format($listing->price, 2) }} PLN</p>

                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('listings.show', $listing) }}" class="btn btn-primary">Zobacz szczegoly</a>

                                @if($isCompared)
                                    <form method="POST" action="{{ route('compare.destroy', $listing) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            Usun z porownania
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('compare.store', $listing) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark btn-sm w-100">
                                            Dodaj do porownania
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">Brak ogloszen do wyswietlenia.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $listings->links() }}
        </div>
    </div>
@endsection
