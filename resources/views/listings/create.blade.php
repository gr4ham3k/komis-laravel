@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

    <body class="bg-light">

        <div class="container py-5">

            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card shadow-sm border-0">

                        <div class="card-body p-4">

                            <h2 class="mb-4">Dodaj ogłoszenie</h2>

                            <form method="POST" action="" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label">Tytuł ogłoszenia</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title') }}" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Opis</label>
                                        <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Cena</label>
                                        <input type="number" step="0.01" name="price" class="form-control"
                                            value="{{ old('price') }}" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Rok produkcji</label>
                                        <input type="number" name="year" class="form-control"
                                            value="{{ old('year') }}" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Kolor</label>
                                        <input type="text" name="color" class="form-control"
                                            value="{{ old('color') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Marka</label>
                                        <input type="text" id="brand-search" class="form-control"
                                            placeholder="Wpisz markę..." autocomplete="off">

                                        <input type="hidden" name="brand_id" id="brand-id">

                                        <div id="brand-results" class="list-group"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Model</label>

                                        <input type="text" id="model-search" class="form-control"
                                            placeholder="Wpisz model..." autocomplete="off">

                                        <input type="hidden" name="model_id" id="model-id">

                                        <div id="model-results" class="list-group"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Paliwo</label>
                                        <select name="fuel_id" class="form-select" required>
                                            <option value="">Wybierz paliwo</option>

                                            @foreach ($fuels as $fuel)
                                                <option value="{{ $fuel->id }}">
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
                                                <option value="{{ $transmission->id }}">
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
                                                <option value="{{ $bodyType->id }}">
                                                    {{ $bodyType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Przebieg (km)</label>
                                        <input type="number" name="mileage" class="form-control"
                                            value="{{ old('mileage') }}" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Pojemność silnika (cm³)</label>
                                        <input type="number" name="engine_capacity" class="form-control"
                                            value="{{ old('engine_capacity') }}" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Moc (KM)</label>
                                        <input type="number" name="power_hp" class="form-control"
                                            value="{{ old('power_hp') }}" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Wybierz lokalizację</label>

                                        <div id="map" style="height: 350px; border-radius: 10px;"></div>

                                        <input type="hidden" name="city" id="city">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Tagi</label>

                                        <div class="row">
                                            @foreach ($tags as $tag)
                                                <div class="col-md-3 col-sm-4 col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="tags[]"
                                                            value="{{ $tag->id }}" id="tag{{ $tag->id }}">

                                                        <label class="form-check-label" for="tag{{ $tag->id }}">
                                                            {{ $tag->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @csrf

                                    <div class="col-12 mt-4">
                                        <button class="btn btn-primary px-4">
                                            Dodaj ogłoszenie
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

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

                        console.log("Wybrane miasto:", city);
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

                const response = await fetch(
                    `/brands/search?q=${encodeURIComponent(search.value)}`
                );

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

                if (modelSearch.value.length < 2) {
                    modelResults.innerHTML = '';
                    return;
                }

                const response = await fetch(
                    `/models/search?brand_id=${brandId.value}&q=${encodeURIComponent(modelSearch.value)}`
                );

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
    @endsection
