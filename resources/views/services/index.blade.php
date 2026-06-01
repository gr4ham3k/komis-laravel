{{-- resources/views/services/index.blade.php --}}
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Usługi motoryzacyjne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f8;
            color: #1f2933;
        }

        .services-shell {
            max-width: 1120px;
        }

        .search-panel,
        .service-offer {
            background: #ffffff;
            border: 1px solid #d8dee6;
            border-radius: 6px;
        }

        .search-panel {
            padding: 18px;
        }

        .service-offer {
            display: grid;
            grid-template-columns: 156px minmax(0, 1fr) 180px;
            gap: 18px;
            padding: 14px;
            text-decoration: none;
            color: inherit;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .service-offer:hover {
            border-color: #1f6feb;
            box-shadow: 0 4px 14px rgba(31, 41, 51, .08);
            color: inherit;
        }

        .service-thumb {
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 4px;
            background: #e8edf3;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #52616f;
            font-size: 34px;
        }

        .service-title {
            font-size: 1.08rem;
            line-height: 1.3;
        }

        .service-description {
            color: #52616f;
            margin-bottom: 12px;
        }

        .service-meta {
            color: #66788a;
            font-size: .92rem;
        }

        .service-price {
            text-align: right;
            font-size: 1.18rem;
            font-weight: 700;
            color: #108043;
            white-space: nowrap;
        }

        .rating-line {
            color: #66788a;
            font-size: .92rem;
        }

        @media (max-width: 767.98px) {
            .service-offer {
                grid-template-columns: 104px minmax(0, 1fr);
            }

            .service-price {
                grid-column: 2;
                text-align: left;
                margin-top: -8px;
            }
        }
    </style>
</head>
<body>

<div class="container services-shell my-4">
    <div class="mb-3">
        <div>
            <h1 class="h3 mb-1">Usługi motoryzacyjne</h1>
            <p class="text-muted mb-0">Znajdź warsztat, diagnostę albo pomoc przy samochodzie.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('services.index') }}" class="search-panel mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-10">
                <label for="search" class="form-label">Czego szukasz?</label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    class="form-control form-control-lg"
                    placeholder="Nazwa usługi, opis albo miasto"
                >
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i>
                    <span class="ms-1">Szukaj</span>
                </button>
            </div>
        </div>

        @if($search !== '')
            <div class="mt-3">
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary btn-sm">
                    Wyczyść wyszukiwanie
                </a>
            </div>
        @endif
    </form>

    <div class="d-flex flex-column gap-2">
        @forelse($services as $service)
            <a href="{{ route('services.show', $service->id) }}" class="service-offer">
                <div class="service-thumb">
                    <i class="fas fa-screwdriver-wrench"></i>
                </div>

                <div>
                    <h2 class="service-title mb-2">{{ $service->title }}</h2>
                    <p class="service-description">{{ Str::limit($service->description, 150) }}</p>

                    <div class="service-meta d-flex flex-wrap gap-3">
                        <span><i class="fas fa-map-marker-alt"></i> {{ $service->city }}</span>
                        <span><i class="fas fa-calendar"></i> {{ $service->created_at->format('d.m.Y') }}</span>
                    </div>

                    <div class="rating-line mt-2">
                        @php $avgRating = $service->averageRating(); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($avgRating))
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <span class="ms-1">({{ $service->reviews->count() }})</span>
                    </div>
                </div>

                <div class="service-price">
                    {{ number_format($service->price, 2) }} PLN
                </div>
            </a>
        @empty
            <div class="alert alert-info">
                @if($search !== '')
                    Brak usług pasujących do wyszukiwania.
                @else
                    Brak dostępnych usług.
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
