{{-- resources/views/services/create.blade.php --}}
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
                        <h2 class="mb-0">Dodaj nową usługę</h2>
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Powrót
                        </a>
                    </div>

                    <form method="POST" action="{{ route('services.store') }}">
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

                            <div class="col-12 mb-3">
                                <label class="form-label">Wybierz lokalizację</label>
                                <div id="map" style="height: 350px; border-radius: 10px;"></div>
                                <input type="hidden" name="city" id="city" value="{{ old('city') }}">
                                <small class="text-muted">Kliknij na mapie aby wybrać miasto</small>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inicjalizacja mapy - tak jak w oryginalnej wersji
    const map = L.map('map').setView([52.237049, 21.017532], 12);
    
    // Dodanie warstwy mapy - identyczna konfiguracja
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    
    let marker;
    
    // Obsługa kliknięcia na mapie - identyczna logika
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
            });
    });
</script>
@endpush