<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Tytuł ogłoszenia</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $listing->title ?? '') }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Opis</label>
        <textarea name="description" rows="5" class="form-control" required>{{ old('description', $listing->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Cena</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $listing->price ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Miasto</label>
        <input type="text" name="city" class="form-control" value="{{ old('city', $listing->city ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Rok produkcji</label>
        <input type="number" name="year" class="form-control" value="{{ old('year', $listing->year ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Marka</label>
        <select name="brand_id" class="form-select" required>
            <option value="">Wybierz markę</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" @selected((string) old('brand_id', $listing->brand_id ?? '') === (string) $brand->id)>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Model</label>
        <select name="model_id" class="form-select" required>
            <option value="">Wybierz model</option>
            @foreach ($models as $model)
                <option value="{{ $model->id }}" @selected((string) old('model_id', $listing->model_id ?? '') === (string) $model->id)>{{ $model->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Paliwo</label>
        <select name="fuel_id" class="form-select" required>
            <option value="">Wybierz paliwo</option>
            @foreach ($fuels as $fuel)
                <option value="{{ $fuel->id }}" @selected((string) old('fuel_id', $listing->fuel_id ?? '') === (string) $fuel->id)>{{ $fuel->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Skrzynia biegów</label>
        <select name="transmission_id" class="form-select" required>
            <option value="">Wybierz skrzynię</option>
            @foreach ($transmissions as $transmission)
                <option value="{{ $transmission->id }}" @selected((string) old('transmission_id', $listing->transmission_id ?? '') === (string) $transmission->id)>{{ $transmission->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Typ nadwozia</label>
        <select name="body_type_id" class="form-select" required>
            <option value="">Wybierz nadwozie</option>
            @foreach ($bodyTypes as $bodyType)
                <option value="{{ $bodyType->id }}" @selected((string) old('body_type_id', $listing->body_type_id ?? '') === (string) $bodyType->id)>{{ $bodyType->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Kolor</label>
        <input type="text" name="color" class="form-control" value="{{ old('color', $listing->color ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Przebieg (km)</label>
        <input type="number" name="mileage" class="form-control" value="{{ old('mileage', $listing->mileage ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Pojemność silnika (cm3)</label>
        <input type="number" name="engine_capacity" class="form-control" value="{{ old('engine_capacity', $listing->engine_capacity ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Moc (KM)</label>
        <input type="number" name="power_hp" class="form-control" value="{{ old('power_hp', $listing->power_hp ?? '') }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Zdjęcia</label>
        <input type="file" name="images[]" class="form-control" multiple>
        @if(!empty($listing) && $listing->images->isNotEmpty())
            <small class="text-muted d-block mt-1">Wgranie nowych zdjęć doda je do istniejącej galerii.</small>
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">Tagi</label>
        <div class="row">
            @php $selectedTags = old('tags', isset($listing) ? $listing->tags->pluck('id')->all() : []); @endphp
            @foreach ($tags as $tag)
                <div class="col-md-3 col-sm-4 col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags))>
                        <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
