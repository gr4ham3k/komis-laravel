{{-- resources/views/services/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Dodaj nową usługę</h2>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Powrót
                        </a>
                    </div>

                    <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tytuł usługi</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Opis</label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cena (PLN)</label>
                                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dodaj zdjęcie</label>
                                <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                       multiple accept="image/*">
                                <small class="text-muted">Proszę wybrać zdjęcie(Max 2MB)
                                </small>
                                @error('images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('images.*')
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
                                <input type="hidden" name="city" id="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i>Dodaj usługę
                                </button>
                                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-4 ms-2">
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
    const map = L.map('map').setView([50.0413, 21.9990], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker;

    const cityInput = document.getElementById('city');

    function setMapStatus(text) {
        document.getElementById('map-status').textContent = text;
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
            .catch(function(error) {
                setMapStatus('Błąd geokodowania');
                console.error(error);
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
