@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edytuj ogłoszenie #{{ $listing->id }}</h2>
        <a href="{{ route('admin.listings.index') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-left me-1"></i> Powrót
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
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

                    <form method="POST" action="{{ route('admin.listings.update', $listing->id) }}">
                        @csrf @method('PATCH')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Tytuł</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $listing->title) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Opis</label>
                                <textarea name="description" rows="5" class="form-control" required>{{ old('description', $listing->description) }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Cena (PLN)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $listing->price) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Rok produkcji</label>
                                <input type="number" name="year" class="form-control" value="{{ old('year', $listing->year) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Kolor</label>
                                <input type="text" name="color" class="form-control" value="{{ old('color', $listing->color) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Marka</label>
                                <select name="brand_id" class="form-select" required>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $listing->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Model</label>
                                <select name="model_id" class="form-select" required>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}" {{ old('model_id', $listing->model_id) == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Paliwo</label>
                                <select name="fuel_id" class="form-select" required>
                                    @foreach ($fuels as $fuel)
                                        <option value="{{ $fuel->id }}" {{ old('fuel_id', $listing->fuel_id) == $fuel->id ? 'selected' : '' }}>{{ $fuel->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Skrzynia biegów</label>
                                <select name="transmission_id" class="form-select" required>
                                    @foreach ($transmissions as $transmission)
                                        <option value="{{ $transmission->id }}" {{ old('transmission_id', $listing->transmission_id) == $transmission->id ? 'selected' : '' }}>{{ $transmission->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Nadwozie</label>
                                <select name="body_type_id" class="form-select" required>
                                    @foreach ($bodyTypes as $bodyType)
                                        <option value="{{ $bodyType->id }}" {{ old('body_type_id', $listing->body_type_id) == $bodyType->id ? 'selected' : '' }}>{{ $bodyType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Przebieg (km)</label>
                                <input type="number" name="mileage" class="form-control" value="{{ old('mileage', $listing->mileage) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Pojemność (cm³)</label>
                                <input type="number" name="engine_capacity" class="form-control" value="{{ old('engine_capacity', $listing->engine_capacity) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Moc (KM)</label>
                                <input type="number" name="power_hp" class="form-control" value="{{ old('power_hp', $listing->power_hp) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Miasto</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $listing->city) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status', $listing->status) == 'active' ? 'selected' : '' }}>Aktywne</option>
                                    <option value="inactive" {{ old('status', $listing->status) == 'inactive' ? 'selected' : '' }}>Nieaktywne</option>
                                    <option value="sold" {{ old('status', $listing->status) == 'sold' ? 'selected' : '' }}>Sprzedane</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Wyposażenie (tagi)</label>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($tags as $tag)
                                        <div class="form-check">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-check-input" id="tag_{{ $tag->id }}"
                                                {{ in_array($tag->id, old('tags', $listing->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent fw-semibold">Informacje</div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>Właściciel</dt>
                        <dd>{{ $listing->user->name }} ({{ $listing->user->email }})</dd>
                        <dt>Data dodania</dt>
                        <dd>{{ $listing->created_at->format('d.m.Y H:i') }}</dd>
                        <dt>Wyświetlenia</dt>
                        <dd>{{ $listing->views_count }}</dd>
                        @if ($listing->latitude)
                            <dt>Współrzędne</dt>
                            <dd>{{ $listing->latitude }}, {{ $listing->longitude }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if ($listing->images->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent fw-semibold">Zdjęcia ({{ $listing->images->count() }})</div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach ($listing->images as $image)
                                <div class="col-6">
                                    <img src="{{ asset('storage/' . $image->file_name) }}" class="img-fluid rounded" alt="Zdjęcie">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
