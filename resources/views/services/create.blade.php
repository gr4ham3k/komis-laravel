{{-- resources/views/services/create.blade.php --}}
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj usługę</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4">Dodaj nową usługę</h2>
                    
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
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="5" required>{{ old('description') }}</textarea>
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
                                
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    Dodaj usługę
                                </button>
                                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary px-4 ms-2">
                                    Anuluj
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Inicjalizacja mapy - dokładnie tak samo jak w ogłoszeniach
    const map = L.map('map').setView([50.0413, 21.9990], 6);
    
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

</body>
</html>