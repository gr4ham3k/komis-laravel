<form action="{{ route('listings.index') }}" method="GET" class="filter-panel p-4 border rounded bg-white shadow-sm mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold">Filtrowanie</h5>
        <a href="{{ route('listings.index') }}" class="text-decoration-none text-muted small">Wyczyść filtry</a>
    </div>
    
    <!-- Szukaj -->
    <div class="mb-3">
        <label for="q" class="form-label small fw-semibold">Szukaj</label>
        <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Czego szukasz?">
    </div>

    <!-- Marka i Model -->
    <div class="row g-2 mb-3">
        <div class="col-6">
            <label for="brand_id" class="form-label small fw-semibold">Marka</label>
            <select name="brand_id" id="brand_id" class="form-select">
                <option value="">Wszystkie</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label for="model_id" class="form-label small fw-semibold">Model</label>
            <select name="model_id" id="model_id" class="form-select">
                <option value="">Wszystkie</option>
                @foreach($models as $model)
                    <option value="{{ $model->id }}" @selected(request('model_id') == $model->id) data-brand="{{ $model->brand_id }}">{{ $model->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Cena -->
    <div class="mb-3">
        <label class="form-label small fw-semibold">Cena (PLN)</label>
        <div class="input-group">
            <input type="number" name="price_min" class="form-control" value="{{ request('price_min') }}" placeholder="Od">
            <span class="input-group-text bg-light border-0">-</span>
            <input type="number" name="price_max" class="form-control" value="{{ request('price_max') }}" placeholder="Do">
        </div>
    </div>

    <!-- Rok produkcji -->
    <div class="mb-3">
        <label class="form-label small fw-semibold">Rok produkcji</label>
        <div class="input-group">
            <input type="number" name="year_min" class="form-control" value="{{ request('year_min') }}" placeholder="Od">
            <span class="input-group-text bg-light border-0">-</span>
            <input type="number" name="year_max" class="form-control" value="{{ request('year_max') }}" placeholder="Do">
        </div>
    </div>
    
    <!-- Przebieg -->
    <div class="mb-3">
        <label class="form-label small fw-semibold">Przebieg do (km)</label>
        <input type="number" name="mileage_max" class="form-control" value="{{ request('mileage_max') }}" placeholder="np. 200000">
    </div>

    <!-- Lokalizacja na mapie -->
    <div class="mb-3">
        <label class="form-label small fw-semibold">Lokalizacja</label>
        
        <div class="input-group input-group-sm mb-2">
            <input type="text" id="map-search-input" class="form-control" placeholder="Wpisz miasto (np. Warszawa)">
            <button class="btn btn-outline-secondary" type="button" id="map-search-btn">Szukaj</button>
        </div>

        <div id="filter-map" style="height: 250px; border-radius: 8px; border: 1px solid var(--mk-line); z-index: 1;" class="mb-2"></div>
        <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
        <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">
        
        <select name="radius" id="radius" class="form-select form-select-sm mt-2">
            <option value="5" @selected(request('radius') == '5')>+ 5 km</option>
            <option value="25" @selected(request('radius') == '25')>+ 25 km</option>
            <option value="50" @selected(request('radius') == '50' || (!request()->has('radius') && request('lat')))>+ 50 km</option>
            <option value="150" @selected(request('radius') == '150')>+ 150 km</option>
            <option value="0" @selected(request('radius') == '0')>Cała Polska</option>
        </select>
        <div class="form-text mt-1 d-flex justify-content-between">
            <span id="map-status">Kliknij na mapę, aby wybrać.</span>
            <a href="#" id="clear-location" class="text-danger text-decoration-none" style="display: {{ request('lat') ? 'inline' : 'none' }}">Wyczyść</a>
        </div>
    </div>

    <!-- Dane techniczne (Collapsible) -->
    <div class="accordion accordion-flush mb-4" id="accordionFilters">
        <div class="accordion-item border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed px-0 shadow-none bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTech" aria-expanded="false" aria-controls="collapseTech">
                    <span class="fw-semibold">Szczegóły techniczne</span>
                </button>
            </h2>
            <div id="collapseTech" class="accordion-collapse collapse" data-bs-parent="#accordionFilters">
                <div class="accordion-body px-0 pt-2 pb-0">
                    <!-- Paliwo -->
                    <div class="mb-3">
                        <label for="fuel_id" class="form-label small">Paliwo</label>
                        <select name="fuel_id" id="fuel_id" class="form-select">
                            <option value="">Wszystkie</option>
                            @foreach($fuels as $fuel)
                                <option value="{{ $fuel->id }}" @selected(request('fuel_id') == $fuel->id)>{{ $fuel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Skrzynia biegów -->
                    <div class="mb-3">
                        <label for="transmission_id" class="form-label small">Skrzynia biegów</label>
                        <select name="transmission_id" id="transmission_id" class="form-select">
                            <option value="">Wszystkie</option>
                            @foreach($transmissions as $transmission)
                                <option value="{{ $transmission->id }}" @selected(request('transmission_id') == $transmission->id)>{{ $transmission->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Typ nadwozia -->
                    <div class="mb-3">
                        <label for="body_type_id" class="form-label small">Nadwozie</label>
                        <select name="body_type_id" id="body_type_id" class="form-select">
                            <option value="">Wszystkie</option>
                            @foreach($bodyTypes as $type)
                                <option value="{{ $type->id }}" @selected(request('body_type_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Moc silnika -->
                    <div class="mb-3">
                        <label class="form-label small">Moc min. (KM)</label>
                        <input type="number" name="power_min" class="form-control" value="{{ request('power_min') }}" placeholder="np. 150">
                    </div>
                    
                    <!-- Pojemność silnika -->
                    <div class="mb-3">
                        <label class="form-label small">Pojemność min. (cm³)</label>
                        <input type="number" name="engine_min" class="form-control" value="{{ request('engine_min') }}" placeholder="np. 1998">
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed px-0 shadow-none bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTags" aria-expanded="false" aria-controls="collapseTags">
                    <span class="fw-semibold">Wyposażenie</span>
                </button>
            </h2>
            <div id="collapseTags" class="accordion-collapse collapse" data-bs-parent="#accordionFilters">
                <div class="accordion-body px-0 pt-2 pb-0">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <div class="form-check w-100 mb-1">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags ?? []))>
                                <label class="form-check-label small" for="tag_{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sortowanie -->
    @if(request('sort'))
        <input type="hidden" name="sort" value="{{ request('sort') }}">
    @endif

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary shadow-sm fw-semibold">Pokaż wyniki</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const brandSelect = document.getElementById('brand_id');
        const modelSelect = document.getElementById('model_id');
        
        if (brandSelect && modelSelect) {
            const allModels = Array.from(modelSelect.options).slice(1);

            function filterModels() {
                const selectedBrand = brandSelect.value;
                let validModelSelected = false;
                
                allModels.forEach(option => {
                    if (!selectedBrand || option.dataset.brand === selectedBrand) {
                        option.style.display = '';
                        if (option.selected) validModelSelected = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (modelSelect.selectedIndex > 0 && !validModelSelected) {
                    modelSelect.value = '';
                }
            }

            brandSelect.addEventListener('change', filterModels);
            filterModels();
        }

        // --- Map Logic ---
        const mapEl = document.getElementById('filter-map');
        if (mapEl && typeof L !== 'undefined') {
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const radiusInput = document.getElementById('radius');
            const clearBtn = document.getElementById('clear-location');
            const statusTxt = document.getElementById('map-status');

            const defaultLat = parseFloat(latInput.value) || 52.0693; 
            const defaultLng = parseFloat(lngInput.value) || 19.4803;
            const hasLocation = !!latInput.value;
            
            const map = L.map('filter-map').setView([defaultLat, defaultLng], hasLocation ? 9 : 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = null;
            let circle = null;

            function updateCircle(lat, lng) {
                if (circle) map.removeLayer(circle);
                let r = parseInt(radiusInput.value);
                if (r > 0) {
                    circle = L.circle([lat, lng], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.1,
                        radius: r * 1000 // meters
                    }).addTo(map);
                }
            }

            if (hasLocation) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
                updateCircle(defaultLat, defaultLng);
                statusTxt.innerText = "Wybrano lokalizację.";
            }

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);
                statusTxt.innerText = "Wybrano lokalizację.";
                clearBtn.style.display = 'inline';
                
                updateCircle(lat, lng);
            });
            
            radiusInput.addEventListener('change', function() {
                if (marker) {
                    updateCircle(marker.getLatLng().lat, marker.getLatLng().lng);
                }
            });

            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                latInput.value = '';
                lngInput.value = '';
                if (marker) { map.removeLayer(marker); marker = null; }
                if (circle) { map.removeLayer(circle); circle = null; }
                statusTxt.innerText = "Kliknij na mapę, aby wybrać.";
                this.style.display = 'none';
            });

            // Geocoding search
            const mapSearchBtn = document.getElementById('map-search-btn');
            const mapSearchInput = document.getElementById('map-search-input');

            function performSearch() {
                const query = mapSearchInput.value.trim();
                if (!query) return;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lng = parseFloat(data[0].lon);

                            map.setView([lat, lng], 10);
                            
                            if (marker) {
                                marker.setLatLng([lat, lng]);
                            } else {
                                marker = L.marker([lat, lng]).addTo(map);
                            }

                            latInput.value = lat.toFixed(6);
                            lngInput.value = lng.toFixed(6);
                            statusTxt.innerText = "Znaleziono: " + data[0].display_name.split(',')[0];
                            clearBtn.style.display = 'inline';
                            
                            updateCircle(lat, lng);
                        } else {
                            alert("Nie znaleziono podanego miasta.");
                        }
                    })
                    .catch(err => {
                        console.error("Błąd geokodowania:", err);
                        alert("Wystąpił błąd podczas wyszukiwania.");
                    });
            }

            mapSearchBtn.addEventListener('click', performSearch);
            mapSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        }
    });
</script>
