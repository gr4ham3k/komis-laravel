@extends('layouts.app')

@section('content')

    <body class="bg-light">

        <div class="container py-5">

            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card shadow-sm border-0">

                        <div class="card-body p-4">

                            <h2 class="mb-4">Dodaj ogłoszenie</h2>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label">Tytuł ogłoszenia</label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title') }}" required>

                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Opis</label>
                                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>

                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Cena</label>
                                        <input type="number" step="0.01" name="price"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price') }}" required>

                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Rok produkcji</label>
                                        <input type="number" name="year"
                                            class="form-control @error('year') is-invalid @enderror"
                                            value="{{ old('year') }}" required>

                                        @error('year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Kolor</label>
                                        <input type="text" name="color"
                                            class="form-control @error('color') is-invalid @enderror"
                                            value="{{ old('color') }}" required>

                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Marka</label>
                                        <input type="text" id="brand-search"
                                            class="form-control @error('brand_id') is-invalid @enderror"
                                            placeholder="Wpisz markę..." autocomplete="off"
                                            value="{{ old('brand_id') ? $brands->find(old('brand_id'))?->name : '' }}">
                                        <input type="hidden" name="brand_id" id="brand-id" value="{{ old('brand_id') }}">
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
                                            value="{{ old('model_id') ? $models->find(old('model_id'))?->name : '' }}">
                                        <input type="hidden" name="model_id" id="model-id" value="{{ old('model_id') }}">
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
                                                <option value="{{ $fuel->id }}">
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
                                                <option value="{{ $transmission->id }}">
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
                                                <option value="{{ $bodyType->id }}">
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
                                            value="{{ old('mileage') }}" required>

                                        @error('mileage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Pojemność silnika (cm³)</label>
                                        <input type="number" name="engine_capacity"
                                            class="form-control @error('engine_capacity') is-invalid @enderror"
                                            value="{{ old('engine_capacity') }}" required>

                                        @error('engine_capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Moc (KM)</label>
                                        <input type="number" name="power_hp"
                                            class="form-control @error('power_hp') is-invalid @enderror"
                                            value="{{ old('power_hp') }}" required>

                                        @error('power_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Wybierz lokalizację</label>

                                        <div id="map" style="height: 350px; border-radius: 10px;"></div>

                                        <input type="hidden" name="city" id="city">
                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">

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

    @endsection

@push('scripts')
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

        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;

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
        const response = await fetch(`/models/search?brand_id=${brandId.value}&q=${encodeURIComponent(modelSearch.value)}`);
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
