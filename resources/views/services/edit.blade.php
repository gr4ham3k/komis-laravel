{{-- resources/views/services/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Edytuj usługę</h2>
                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Powrót do usługi
                        </a>
                    </div>

                    <form method="POST" action="{{ route('services.update', $service->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tytuł usługi</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $service->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Opis</label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                          required>{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cena (PLN)</label>
                                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $service->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Aktualne zdjęcia</label>
                                <div class="row g-2">
                                    @forelse($service->images as $image)
                                        <div class="col-4 position-relative">
                                            <img src="{{ asset('storage/' . $image->file_name) }}"
                                                 class="img-fluid rounded border"
                                                 style="height: 80px; width: 100%; object-fit: cover;"
                                                 alt="{{ $image->original_name }}">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox"
                                                       name="delete_images[]" value="{{ $image->id }}"
                                                       id="delete_image_{{ $image->id }}">
                                                <label class="form-check-label small text-danger" for="delete_image_{{ $image->id }}">
                                                    Usuń
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted">Brak zdjęć dla tej usługi.</p>
                                        </div>
                                    @endforelse
                                </div>
                                <small class="text-muted">Zaznacz zdjęcia, które chcesz usunąć.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Dodaj nowe zdjęcie</label>
                                <input type="file" name="new_images[]" class="form-control @error('new_images') is-invalid @enderror @error('new_images.*') is-invalid @enderror"
                                       multiple accept="image/*">
                                <small class="text-muted">Proszę dodać zdjęcie(Max 2MB)</small>
                                @error('new_images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('new_images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Wybierz lokalizację</label>

                                <div class="input-group mb-2">
                                    <input type="text" id="map-search-input" class="form-control"
                                        placeholder="Wpisz miasto (np. Warszawa)">
                                    <button type="button" class="btn btn-outline-secondary" id="map-search-btn">
                                        Szukaj
                                    </button>
                                </div>

                                <div id="map-status" class="text-muted small mt-2"></div>

                                <div id="map" style="height: 350px; border-radius: 10px;"></div>
                                <input type="hidden" name="city" id="city" value="{{ old('city', $service->city) }}">
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i>Zapisz zmiany
                                </button>
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-secondary px-4 ms-2">
                                    <i class="fas fa-times me-1"></i>Anuluj
                                </a>
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
document.addEventListener('DOMContentLoaded', function () {
    const defaultCity = "{{ old('city', $service->city) }}";

    const map = L.map('map').setView([50.0413, 21.9990], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker;

    const cityInput = document.getElementById('city');

    function setMapStatus(text) {
        document.getElementById('map-status').textContent = text;
    }

    if (defaultCity) {
        setMapStatus('Wczytywanie lokalizacji...');

        fetch(`/geocode?q=${encodeURIComponent(defaultCity)}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);

                    map.setView([lat, lng], 10);

                    marker = L.marker([lat, lng]).addTo(map);
                    setMapStatus('Aktualna lokalizacja: ' + defaultCity);
                } else {
                    setMapStatus(defaultCity + ' (nie znaleziono na mapie)');
                }
            })
            .catch(function() {
                setMapStatus('Nie udało się wczytać lokalizacji');
            });
    }

    map.on('click', function(e) {
        if (marker) map.removeLayer(marker);

        marker = L.marker(e.latlng).addTo(map);

        setMapStatus('Pobieram nazwę miasta...');

        fetch(`/geocode/reverse?lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
            .then(r => r.json())
            .then(data => {
                const city = data.address?.city ||
                             data.address?.town ||
                             data.address?.village ||
                             '';
                cityInput.value = city;
                if (city) {
                    setMapStatus('Wybrano: ' + city);
                } else {
                    setMapStatus('Nie rozpoznano miasta');
                }
            })
            .catch(function() {
                setMapStatus('Błąd geokodowania');
            });
    });

    function searchLocation() {
        const query = document.getElementById('map-search-input').value.trim();
        if (!query) return;

        setMapStatus('Szukam lokalizacji...');

        fetch(`/geocode?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    setMapStatus('Nie znaleziono lokalizacji');
                    return;
                }

                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);

                map.setView([lat, lng], 10);

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);

                cityInput.value =
                    data[0].address?.city ||
                    data[0].address?.town ||
                    data[0].address?.village ||
                    data[0].display_name?.split(',')[0] ||
                    '';

                setMapStatus('Znaleziono: ' + cityInput.value);
            })
            .catch(function() {
                setMapStatus('Błąd wyszukiwania');
            });
    }

    document.getElementById('map-search-btn').addEventListener('click', searchLocation);

    document.getElementById('map-search-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchLocation();
        }
    });
});
</script>
@endpush
