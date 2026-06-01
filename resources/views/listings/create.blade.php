@extends('layouts.app')

@section('title', 'Dodaj ogłoszenie - MotoKomis')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Nowe ogłoszenie</p>
                    <h1 class="h2 mb-1">Dodaj ogłoszenie</h1>
                    <p class="text-muted mb-0">Uzupełnij parametry auta i wskaż lokalizację na mapie.</p>
                </div>
                <a href="{{ route('listings.index') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-1"></i> Wróć do ogłoszeń
                </a>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="filter-panel p-4">
                    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tytuł ogłoszenia</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Opis</label>
                                <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Cena</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Rok produkcji</label>
                                <input type="number" name="year" class="form-control" value="{{ old('year') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Kolor</label>
                                <input type="text" name="color" class="form-control" value="{{ old('color') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Marka</label>
                                <select name="brand_id" class="form-select" required>
                                    <option value="">Wybierz markę</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" @selected((string) old('brand_id') === (string) $brand->id)>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Model</label>
                                <select name="model_id" class="form-select" required>
                                    <option value="">Wybierz model</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}" @selected((string) old('model_id') === (string) $model->id)>
                                            {{ $model->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Paliwo</label>
                                <select name="fuel_id" class="form-select" required>
                                    <option value="">Wybierz paliwo</option>
                                    @foreach ($fuels as $fuel)
                                        <option value="{{ $fuel->id }}" @selected((string) old('fuel_id') === (string) $fuel->id)>
                                            {{ $fuel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Skrzynia biegów</label>
                                <select name="transmission_id" class="form-select" required>
                                    <option value="">Wybierz skrzynię</option>
                                    @foreach ($transmissions as $transmission)
                                        <option value="{{ $transmission->id }}" @selected((string) old('transmission_id') === (string) $transmission->id)>
                                            {{ $transmission->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Typ nadwozia</label>
                                <select name="body_type_id" class="form-select" required>
                                    <option value="">Wybierz nadwozie</option>
                                    @foreach ($bodyTypes as $bodyType)
                                        <option value="{{ $bodyType->id }}" @selected((string) old('body_type_id') === (string) $bodyType->id)>
                                            {{ $bodyType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Przebieg (km)</label>
                                <input type="number" name="mileage" class="form-control" value="{{ old('mileage') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Pojemność silnika (cm³)</label>
                                <input type="number" name="engine_capacity" class="form-control" value="{{ old('engine_capacity') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Moc (KM)</label>
                                <input type="number" name="power_hp" class="form-control" value="{{ old('power_hp') }}" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Wybierz lokalizację</label>
                                <div id="map" style="height: 350px; border-radius: 8px;"></div>
                                <input type="hidden" name="city" id="city" value="{{ old('city') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Tagi</label>
                                <div class="row g-2">
                                    @foreach ($tags as $tag)
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="tags[]"
                                                    value="{{ $tag->id }}"
                                                    id="tag{{ $tag->id }}"
                                                    @checked(in_array($tag->id, old('tags', [])))
                                                >
                                                <label class="form-check-label" for="tag{{ $tag->id }}">
                                                    {{ $tag->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button class="btn btn-mk px-4">
                                    Dodaj ogłoszenie
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([50.0413, 21.9990], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker;

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker(e.latlng).addTo(map);

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                .then(res => res.json())
                .then(data => {
                    const city =
                        data.address.city ||
                        data.address.town ||
                        data.address.village ||
                        data.address.state ||
                        '';

                    document.getElementById('city').value = city;
                });
        });
    </script>
@endpush
