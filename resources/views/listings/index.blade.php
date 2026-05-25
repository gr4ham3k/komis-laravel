<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Ogłoszenia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Ogłoszenia</h1>
        <a href="{{ route('listings.create') }}" class="btn btn-primary">Dodaj ogłoszenie</a>
    </div>

    <div class="row">
        @forelse($listings as $listing)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    @if($listing->images->first())
                        <img
                            src="{{ asset('storage/listings/' . $listing->images->first()->file_name) }}"
                            class="card-img-top"
                            alt="{{ $listing->title }}"
                            style="height: 220px; object-fit: cover;"
                        >
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $listing->title }}</h5>
                        <p class="card-text text-muted mb-1">{{ $listing->city }}</p>
                        <p class="card-text fw-bold text-success">{{ number_format($listing->price, 0, ',', ' ') }} PLN</p>
                        <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-outline-primary">Zobacz więcej</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Brak ogłoszeń.</div>
            </div>
        @endforelse
    </div>

    {{ $listings->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
