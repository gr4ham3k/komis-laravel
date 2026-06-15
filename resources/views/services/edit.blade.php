{{-- resources/views/services/edit.blade.php --}}
{{-- resources/views/services/edit.blade.php --}}
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

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
                                            <img src="{{ asset('storage/services/' . $image->file_name) }}"
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
                                <div id="map" style="height: 350px; border-radius: 10px;"></div>
                                <input type="hidden" name="city" id="city" value="{{ old('city', $service->city) }}">
                                <small class="text-muted">Kliknij na mapie aby zmienić miasto</small>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inicjalizacja mapy
    const defaultCity = "{{ old('city', $service->city) }}";

    // Początkowe współrzędne (domyślnie Warszawa, potem geokodowanie dla miasta)
    let initialLat = 52.237049;
    let initialLng = 21.017532;

    const map = L.map('map').setView([initialLat, initialLng], 12);

    // Dodanie warstwy mapy
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let marker;

    // Jeśli mamy zapisane miasto, spróbujemy znaleźć jego współrzędne i ustawić marker
    if (defaultCity) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(defaultCity)}&limit=1`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);

                    map.setView([lat, lon], 12);

                    marker = L.marker([lat, lon]).addTo(map);
                }
            })
            .catch(error => console.error('Geokodowanie błędu:', error));
    }

    // Obsługa kliknięcia na mapie
    map.on('click', function(e) {
        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker(e.latlng).addTo(map);

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
            .then(res => res.json())
            .then(data => {
                const city = data.address.city ||
                           data.address.town ||
                           data.address.village ||
                           data.address.state ||
                           '';

                document.getElementById('city').value = city;

                console.log("Wybrane miasto:", city);
            })
            .catch(error => console.error('Błąd odwrotnego geokodowania:', error));
    });
</script>
@endpush
