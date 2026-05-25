@extends('layouts.app')

@section('content')
<div class="bg-dark text-white py-5 mb-4">
    <div class="container">
        <h1 class="display-5 fw-bold">Znajdź auto dla siebie</h1>
        <p class="lead mb-4">Ogłoszenia z filtrowaniem, sortowaniem i szybkim podglądem szczegółów.</p>

        <form method="GET" action="{{ route('listings.index') }}" class="row g-2">
            <div class="col-md-3">
                <input type="text" class="form-control" name="city" placeholder="Miasto">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="brand_id">
                    <option value="">Marka</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="min_price" placeholder="Cena od">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="max_price" placeholder="Cena do">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-warning fw-semibold" type="submit">Szukaj</button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Najnowsze ogłoszenia</h2>
        <a href="{{ route('listings.index') }}" class="btn btn-outline-dark">Zobacz wszystkie</a>
    </div>

    <div class="row g-3">
        @forelse($latestListings as $listing)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm">
                    @php $image = $listing->images->first(); @endphp
                    @if($image)
                        <img src="{{ asset('storage/listings/' . $image->file_name) }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <h3 class="h6 mb-1">{{ $listing->title }}</h3>
                        <div class="text-muted small mb-2">{{ $listing->brand->name ?? '-' }} {{ $listing->carModel->name ?? '' }}</div>
                        <div class="fw-bold text-success mb-2">{{ number_format($listing->price, 0, ',', ' ') }} PLN</div>
                        <div class="small text-muted mb-3">{{ $listing->city }} • {{ $listing->year }}</div>
                        <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-sm btn-primary">Szczegóły</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Brak ogłoszeń do wyświetlenia.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
