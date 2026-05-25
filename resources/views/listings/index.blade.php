@extends('layouts.site')

@section('title', $isHome ? 'Strona glowna' : 'Lista ogloszen')

@section('content')
    <style>
        .hero-wrap {
            background: radial-gradient(circle at 15% 20%, #ffedd5 0%, #fff7ed 34%, #f8fafc 60%),
                        linear-gradient(135deg, #0f172a 0%, #111827 42%, #334155 100%);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            color: #0f172a;
            border: 1px solid #e5e7eb;
        }

        .hero-wrap::after {
            content: '';
            position: absolute;
            right: -100px;
            top: -90px;
            width: 290px;
            height: 290px;
            border-radius: 50%;
            background: rgba(251, 146, 60, 0.28);
            filter: blur(2px);
        }

        .hero-dark {
            color: #f9fafb;
            background: linear-gradient(140deg, rgba(15, 23, 42, 0.94), rgba(30, 41, 59, 0.9));
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

        .hero-kpi {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 14px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .hero-kpi-label {
            color: #64748b;
            font-size: 0.82rem;
            margin-bottom: 2px;
        }

        .hero-kpi-value {
            color: #0f172a;
            font-size: 1.3rem;
            font-weight: 700;
            line-height: 1.15;
        }

        .filter-card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 16px 44px rgba(17, 24, 39, 0.12);
        }

        .listing-tile {
            transition: transform .18s ease, box-shadow .18s ease;
            border-radius: 14px;
            overflow: hidden;
        }

        .listing-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.16);
        }

        .tag-chip {
            border: 1px solid #d1d5db;
            border-radius: 999px;
            padding: 6px 12px;
            background: #fff;
            color: #374151;
            font-size: 0.88rem;
        }

        .tag-chip input {
            margin-right: 6px;
        }

        .tag-badge {
            background: #e5e7eb;
            color: #1f2937;
            border-radius: 999px;
            font-size: 0.72rem;
            padding: 4px 10px;
            margin-right: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .hero-badge {
            background: rgba(30, 41, 59, 0.08);
            border: 1px solid rgba(100, 116, 139, 0.25);
            color: #334155;
            font-size: 0.85rem;
            padding: 6px 11px;
            border-radius: 999px;
            display: inline-block;
        }
    </style>
    @php
        $resolveImagePath = function ($fileName) {
            if (! $fileName) {
                return 'https://via.placeholder.com/300x200?text=Brak+zdjecia';
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

    @if ($isHome)
        <div class="hero-wrap p-4 p-lg-5 mb-4">
            <div class="row g-4 align-items-center position-relative" style="z-index:1;">
                <div class="col-lg-7">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="hero-badge">Sprawdzone oferty</span>
                        <span class="hero-badge">Filtry i sortowanie</span>
                        <span class="hero-badge">Szybki podglad</span>
                    </div>
                    <h1 class="display-6 fw-bold mb-3">Znajdz auto szybciej i porownaj je bez chaosu</h1>
                    <p class="lead mb-4">
                        Filtruj po marce, cenie i tagach. W kilka sekund zobaczysz, ktore auto bardziej pasuje: miejskie,
                        rodzinne czy wyscigowe.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @auth
                            <a href="{{ route('listings.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Dodaj ogloszenie
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fa-solid fa-right-to-bracket"></i> Zaloguj i dodaj
                            </a>
                        @endauth
                        <a href="{{ route('listings.index', ['sort' => 'popular']) }}" class="btn btn-outline-dark">
                            <i class="fa-solid fa-fire"></i> Popularne teraz
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-dark p-4">
                        <p class="mb-1 text-uppercase small">Dostepne ogloszenia</p>
                        <p class="display-6 fw-bold mb-2">{{ $listings->total() }}</p>
                        <p class="mb-3 small text-light">Sortuj po cenie, popularnosci i dacie dodania.</p>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="hero-kpi">
                                    <p class="hero-kpi-label">Marki</p>
                                    <p class="hero-kpi-value mb-0">{{ $brands->count() }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-kpi">
                                    <p class="hero-kpi-label">Miast</p>
                                    <p class="hero-kpi-value mb-0">{{ $listings->pluck('city')->unique()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('listings.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Marka</label>
                        <select class="form-select" name="brand_id" id="brandSelect">
                            <option value="">Wszystkie</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(($filters['brand_id'] ?? '') == $brand->id)>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Model</label>
                        <select class="form-select" name="model_id" id="modelSelect">
                            <option value="">Wszystkie</option>
                            @foreach ($models as $model)
                                <option value="{{ $model->id }}" data-brand-id="{{ $model->brand_id }}" @selected(($filters['model_id'] ?? '') == $model->id)>
                                    {{ $model->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cena od</label>
                        <input class="form-control" type="number" min="0" name="price_from" value="{{ $filters['price_from'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cena do</label>
                        <input class="form-control" type="number" min="0" name="price_to" value="{{ $filters['price_to'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Miasto</label>
                        <input class="form-control" type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="np. Rzeszow">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Rok od</label>
                        <input class="form-control" type="number" min="1950" name="year_from" value="{{ $filters['year_from'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Rok do</label>
                        <input class="form-control" type="number" min="1950" name="year_to" value="{{ $filters['year_to'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Paliwo</label>
                        <select class="form-select" name="fuel_id">
                            <option value="">Wszystkie</option>
                            @foreach ($fuels as $fuel)
                                <option value="{{ $fuel->id }}" @selected(($filters['fuel_id'] ?? '') == $fuel->id)>
                                    {{ $fuel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Skrzynia biegow</label>
                        <select class="form-select" name="transmission_id">
                            <option value="">Wszystkie</option>
                            @foreach ($transmissions as $transmission)
                                <option value="{{ $transmission->id }}" @selected(($filters['transmission_id'] ?? '') == $transmission->id)>
                                    {{ $transmission->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sortowanie</label>
                        <select class="form-select" name="sort">
                            <option value="">Najnowsze</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>Cena rosnaco</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>Cena malejaco</option>
                            <option value="year_asc" @selected($sort === 'year_asc')>Rocznik rosnaco</option>
                            <option value="year_desc" @selected($sort === 'year_desc')>Rocznik malejaco</option>
                            <option value="popular" @selected($sort === 'popular')>Popularnosc</option>
                            <option value="oldest" @selected($sort === 'oldest')>Najstarsze</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Widok</label>
                        <select class="form-select" name="layout">
                            <option value="grid" @selected($layout === 'grid')>Siatka</option>
                            <option value="list" @selected($layout === 'list')>Lista</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button class="btn btn-primary w-100" type="submit">Filtruj</button>
                        <a class="btn btn-outline-secondary w-100" href="{{ route('listings.index') }}">Wyczysc</a>
                    </div>

                    <div class="col-12 pt-2">
                        <label class="form-label d-block mb-2">Tagi</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <label class="tag-chip">
                                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $filters['tag_ids'] ?? [], false))>
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Ogloszenia ({{ $listings->total() }})</h2>
        <small class="text-secondary">Strona {{ $listings->currentPage() }} z {{ $listings->lastPage() }}</small>
    </div>

    @if ($listings->isEmpty())
        <div class="alert alert-info">Brak ogloszen dla wybranych filtrow.</div>
    @else
        @if ($layout === 'list')
            <div class="vstack gap-3">
                @foreach ($listings as $listing)
                    @php
                        $image = $listing->images->first();
                        $imgPath = $resolveImagePath($image?->file_name);
                    @endphp
                    <a href="{{ route('listings.show', $listing) }}" class="card listing-tile text-decoration-none text-dark border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <img src="{{ $imgPath }}" class="img-fluid h-100 w-100 object-fit-cover" alt="{{ $listing->title }}">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title mb-1">{{ $listing->title }}</h5>
                                        <span class="fs-5 fw-semibold">{{ number_format((float) $listing->price, 0, ',', ' ') }} PLN</span>
                                    </div>
                                    <p class="card-text text-secondary mb-2">
                                        {{ optional($listing->brand)->name ?? 'Brak marki' }}
                                        {{ optional($listing->carModel)->name ?? 'Brak modelu' }}
                                        | {{ $listing->year }} | {{ number_format($listing->mileage, 0, ',', ' ') }} km
                                    </p>
                                    <p class="card-text mb-2">{{ \Illuminate\Support\Str::limit($listing->description, 130) }}</p>

                                    <div class="mb-2">
                                        @foreach ($listing->tags as $tag)
                                            <span class="tag-badge">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>

                                    <small class="text-secondary">
                                        {{ $listing->city }} | {{ ucfirst(optional($listing->transmission)->name ?? 'brak danych') }} | wyswietlenia: {{ $listing->views_count }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="row g-4">
                @foreach ($listings as $listing)
                    @php
                        $image = $listing->images->first();
                        $imgPath = $resolveImagePath($image?->file_name);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('listings.show', $listing) }}" class="card listing-tile h-100 text-decoration-none text-dark border-0 shadow-sm">
                            <img src="{{ $imgPath }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $listing->title }}</h5>
                                <p class="card-text text-secondary mb-2">{{ optional($listing->brand)->name ?? 'Brak marki' }} {{ optional($listing->carModel)->name ?? 'Brak modelu' }}</p>
                                <p class="card-text mb-2">{{ $listing->city }} | {{ $listing->year }}</p>
                                <p class="card-text fw-semibold fs-5 mb-2">{{ number_format((float) $listing->price, 0, ',', ' ') }} PLN</p>
                                <div>
                                    @foreach ($listing->tags as $tag)
                                        <span class="tag-badge">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">
            {{ $listings->links() }}
        </div>
    @endif

    <script>
        (function () {
            const brandSelect = document.getElementById('brandSelect');
            const modelSelect = document.getElementById('modelSelect');
            const selectedModelId = '{{ $filters['model_id'] ?? '' }}';

            function filterModels() {
                const selectedBrand = brandSelect.value;

                Array.from(modelSelect.options).forEach((option) => {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }

                    const optionBrandId = option.dataset.brandId;
                    option.hidden = selectedBrand && optionBrandId !== selectedBrand;
                });

                const selectedOption = modelSelect.querySelector(`option[value="${selectedModelId}"]`);
                if (selectedOption && selectedOption.hidden) {
                    modelSelect.value = '';
                }
            }

            brandSelect.addEventListener('change', filterModels);
            filterModels();
        })();
    </script>
@endsection
