@extends('layouts.app')

@section('content')
    @php
        $compareIds = session('compare_listings', []);
    @endphp

    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Ogłoszenia</h1>
                <p class="text-muted mb-0">Przeglądaj aktywne oferty dodane przez użytkowników.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('compare.index') }}" class="btn btn-outline-dark">
                    Porównaj
                    @if(!empty($compareIds))
                        <span class="badge text-bg-dark ms-1">{{ count($compareIds) }}</span>
                    @endif
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Powrót</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-4">
                @include('listings._filters')
            </div>

            <div class="col-lg-9 col-md-8">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <p class="text-muted mb-0 small">Znaleziono: {{ $listings->total() }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="sort" class="form-label small fw-semibold mb-0">Sortuj:</label>
                            <select name="sort" id="sort" class="form-select form-select-sm" style="width: auto;" onchange="location.href='{{ route('listings.index') }}?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(location.search)), sort: this.value}).toString()">
                                <option value="" @selected(!request('sort'))>Najnowsze</option>
                                <option value="price_asc" @selected(request('sort') == 'price_asc')>Cena rosnąco</option>
                                <option value="price_desc" @selected(request('sort') == 'price_desc')>Cena malejąco</option>
                                <option value="year_desc" @selected(request('sort') == 'year_desc')>Najnowsze roczniki</option>
                                <option value="mileage_asc" @selected(request('sort') == 'mileage_asc')>Najniższy przebieg</option>
                                <option value="popular" @selected(request('sort') == 'popular')>Najpopularniejsze</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label for="per_page" class="form-label small fw-semibold mb-0">Na stronie:</label>
                            <select name="per_page" id="per_page" class="form-select form-select-sm" style="width: auto;" onchange="location.href='{{ route('listings.index') }}?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(location.search)), per_page: this.value, page: 1}).toString()">
                                <option value="10" @selected(request('per_page', 12) == 10)>10</option>
                                <option value="20" @selected(request('per_page', 12) == 20)>20</option>
                                <option value="50" @selected(request('per_page', 12) == 50)>50</option>
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('listings.index', ['view' => 'grid'] + request()->except('view')) }}" class="btn btn-outline-secondary {{ $viewMode === 'grid' ? 'active' : '' }}" title="Widok siatki">
                                <i class="fas fa-th"></i>
                            </a>
                            <a href="{{ route('listings.index', ['view' => 'list'] + request()->except('view')) }}" class="btn btn-outline-secondary {{ $viewMode === 'list' ? 'active' : '' }}" title="Widok listy">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="{{ $viewMode === 'list' ? 'd-flex flex-column gap-3' : 'row g-4' }}">
                    @forelse ($listings as $listing)
                        @if($viewMode === 'list')
                            @include('listings._card_list', ['listing' => $listing])
                        @else
                            <div class="col-md-6 col-xl-4">
                                @include('listings._card', ['listing' => $listing])
                            </div>
                        @endif
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">Brak ogłoszeń spełniających wybrane kryteria.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $listings->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
