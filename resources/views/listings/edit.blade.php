@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Edytuj ogłoszenie</h2>
                    <a href="{{ route('my.listings') }}" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-1"></i> Powrót
                    </a>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($listing->images->count() > 0)
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">
                                        Zdjęcia ({{ $listing->images->count() }})
                                    </span>

                                    <a href="{{ route('listings.images.edit', $listing) }}" class="btn btn-sm btn-primary">
                                        Zarządzaj
                                    </a>
                                </div>

                                <div class="card-body">
                                    <div class="row g-2">
                                        @foreach ($listing->images as $image)
                                            <div class="col-6 col-md-4">
                                                <img src="{{ asset('storage/' . $image->file_name) }}"
                                                    class="img-fluid rounded shadow-sm"
                                                    style="aspect-ratio: 4/3; object-fit: cover;" alt="Zdjęcie">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Brak zdjęć</strong><br>
                                        <small class="text-muted">Dodaj zdjęcia do ogłoszenia</small>
                                    </div>

                                    <a href="{{ route('listings.images.edit', $listing) }}" class="btn btn-primary">
                                        Dodaj zdjęcia
                                    </a>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('listings.update', $listing) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Tytuł ogłoszenia</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $listing->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Opis</label>
                                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $listing->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cena</label>
                                    <input type="number" step="0.01" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', $listing->price) }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Rok produkcji</label>
                                    <input type="number" name="year"
                                        class="form-control @error('year') is-invalid @enderror"
                                        value="{{ old('year', $listing->year) }}" required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Kolor</label>
                                    <input type="text" name="color"
                                        class="form-control @error('color') is-invalid @enderror"
                                        value="{{ old('color', $listing->color) }}" required>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Marka</label>
                                    <input type="text" id="brand-search"
                                        class="form-control @error('brand_id') is-invalid @enderror"
                                        placeholder="Wpisz markę..." autocomplete="off"
                                        value="{{ old('brand_id') ? $brands->find(old('brand_id'))?->name : $listing->brand->name }}">
                                    <input type="hidden" name="brand_id" id="brand-id"
                                        value="{{ old('brand_id', $listing->brand_id) }}">
                                    <div id="brand-results" class="list-group"></div>
                                    @error('brand_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Model</label>
                                    <input type="text" id="model-search"
                                        class="form-control @error('model_id') is-invalid @enderror"
                                        placeholder="Wpisz model..." autocomplete="off"
                                        value="{{ old('model_id') ? $models->find(old('model_id'))?->name : $listing->carModel->name }}">
                                    <input type="hidden" name="model_id" id="model-id"
                                        value="{{ old('model_id', $listing->model_id) }}">
                                    <div id="model-results" class="list-group"></div>
                                    @error('model_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Paliwo</label>
                                    <select name="fuel_id" class="form-select @error('fuel_id') is-invalid @enderror"
                                        required>
                                        <option value="">Wybierz paliwo</option>
                                        @foreach ($fuels as $fuel)
                                            <option value="{{ $fuel->id }}"
                                                {{ old('fuel_id', $listing->fuel_id) == $fuel->id ? 'selected' : '' }}>
                                                {{ $fuel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fuel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Skrzynia biegów</label>
                                    <select name="transmission_id"
                                        class="form-select @error('transmission_id') is-invalid @enderror" required>
                                        <option value="">Wybierz skrzynię</option>
                                        @foreach ($transmissions as $transmission)
                                            <option value="{{ $transmission->id }}"
                                                {{ old('transmission_id', $listing->transmission_id) == $transmission->id ? 'selected' : '' }}>
                                                {{ $transmission->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('transmission_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Typ nadwozia</label>
                                    <select name="body_type_id"
                                        class="form-select @error('body_type_id') is-invalid @enderror" required>
                                        <option value="">Wybierz nadwozie</option>
                                        @foreach ($bodyTypes as $bodyType)
                                            <option value="{{ $bodyType->id }}"
                                                {{ old('body_type_id', $listing->body_type_id) == $bodyType->id ? 'selected' : '' }}>
                                                {{ $bodyType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('body_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Przebieg (km)</label>
                                    <input type="number" name="mileage"
                                        class="form-control @error('mileage') is-invalid @enderror"
                                        value="{{ old('mileage', $listing->mileage) }}" required>
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Pojemność silnika (cm³)</label>
                                    <input type="number" name="engine_capacity"
                                        class="form-control @error('engine_capacity') is-invalid @enderror"
                                        value="{{ old('engine_capacity', $listing->engine_capacity) }}" required>
                                    @error('engine_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Moc (KM)</label>
                                    <input type="number" name="power_hp"
                                        class="form-control @error('power_hp') is-invalid @enderror"
                                        value="{{ old('power_hp', $listing->power_hp) }}" required>
                                    @error('power_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Wybierz lokalizację</label>

                                    <div class="input-group mb-2">
                                        <input type="text" id="map-search-input" class="form-control"
                                            placeholder="Wpisz miasto (np. Warszawa)">
                                        <button type="button" id="map-search-btn" class="btn btn-outline-secondary">
                                            Szukaj
                                        </button>
                                    </div>

                                    <div id="map-status" class="text-muted small mt-2"></div>

                                    <div id="map" style="height: 350px; border-radius: 10px;"></div>
                                    <input type="hidden" name="city" id="city"
                                        value="{{ old('city', $listing->city) }}">
                                    <input type="hidden" name="latitude" id="latitude"
                                        value="{{ old('latitude', $listing->latitude) }}">
                                    <input type="hidden" name="longitude" id="longitude"
                                        value="{{ old('longitude', $listing->longitude) }}">
                                    @error('city')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Tagi</label>
                                    <div class="row">
                                        @foreach ($tags as $tag)
                                            <div class="col-md-3 col-sm-4 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="tags[]"
                                                        value="{{ $tag->id }}" id="tag{{ $tag->id }}"
                                                        {{ in_array($tag->id, old('tags', $listing->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tag{{ $tag->id }}">
                                                        {{ $tag->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <button class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Zapisz zmiany
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const initialLat = {{ $listing->latitude ?? 50.0413 }};
            const initialLng = {{ $listing->longitude ?? 21.999 }};
            const hasLocation = {{ $listing->latitude && $listing->longitude ? 'true' : 'false' }};

            const map = L.map('map').setView([initialLat, initialLng], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = null;

            if (hasLocation) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
            }

            document.getElementById('latitude').value = initialLat;
            document.getElementById('longitude').value = initialLng;

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const cityInput = document.getElementById('city');

            function setMapStatus(text) {
                document.getElementById('map-status').textContent = text;
            }

            function searchLocation() {
                const query = document.getElementById('map-search-input').value.trim();
                if (!query) return;

                setMapStatus('🔎 Szukam lokalizacji...');

                fetch(`/geocode?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {

                        if (!data.length) {
                            setMapStatus('❌ Nie znaleziono lokalizacji');
                            return;
                        }

                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);

                        map.setView([lat, lng], 10);

                        if (marker) {
                            marker.setLatLng([lat, lng]);
                        } else {
                            marker = L.marker([lat, lng]).addTo(map);
                        }

                        latInput.value = lat;
                        lngInput.value = lng;

                        cityInput.value =
                            data[0].address?.city ||
                            data[0].address?.town ||
                            data[0].address?.village ||
                            data[0].display_name.split(',')[0];

                        setMapStatus('📍 Znaleziono lokalizację');
                    })
                    .catch(() => {
                        setMapStatus('❌ Błąd wyszukiwania');
                    });
            }

            document.getElementById('map-search-btn')
                .addEventListener('click', searchLocation);

            document.getElementById('map-search-input')
                .addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchLocation();
                    }
                });

            map.on('click', function(e) {

                if (marker) map.removeLayer(marker);

                marker = L.marker(e.latlng).addTo(map);

                latInput.value = e.latlng.lat;
                lngInput.value = e.latlng.lng;

                fetch(`/geocode/reverse?lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                    .then(r => r.json())
                    .then(data => {
                        cityInput.value =
                            data.address?.city ||
                            data.address?.town ||
                            data.address?.village ||
                            '';
                    });
            });

        });
    </script>

    <script>
        const search = document.getElementById('brand-search');
        const results = document.getElementById('brand-results');
        const brandId = document.getElementById('brand-id');

        search.addEventListener('input', async () => {
            if (search.value.length < 1) {
                results.innerHTML = '';
                return;
            }
            const response = await fetch(`/brands/search?q=${encodeURIComponent(search.value)}`);
            const brands = await response.json();
            results.innerHTML = '';
            brands.forEach(brand => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = brand.name;
                item.onclick = () => {
                    search.value = brand.name;
                    brandId.value = brand.id;
                    results.innerHTML = '';
                    modelSearch.value = '';
                    modelId.value = '';
                    modelResults.innerHTML = '';
                };
                results.appendChild(item);
            });
        });
    </script>

    <script>
        const modelSearch = document.getElementById('model-search');
        const modelResults = document.getElementById('model-results');
        const modelId = document.getElementById('model-id');

        modelSearch.addEventListener('input', async () => {
            if (!brandId.value) return;
            if (modelSearch.value.length < 1) {
                modelResults.innerHTML = '';
                return;
            }
            const response = await fetch(
                `/models/search?brand_id=${brandId.value}&q=${encodeURIComponent(modelSearch.value)}`);
            const models = await response.json();
            modelResults.innerHTML = '';
            models.forEach(model => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = model.name;
                item.onclick = () => {
                    modelSearch.value = model.name;
                    modelId.value = model.id;
                    modelResults.innerHTML = '';
                };
                modelResults.appendChild(item);
            });
        });
    </script>
@endpush
