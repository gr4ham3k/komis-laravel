<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj ogłoszenie</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

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
                                    <label class="form-label">Miasto</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city') }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Rok produkcji</label>
                                    <input type="number" name="year" class="form-control"
                                        value="{{ old('year') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Marka</label>
                                    <select name="brand_id" class="form-select" required>
                                        <option value="">Wybierz markę</option>

                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Model</label>
                                    <select name="model_id" class="form-select" required>
                                        <option value="">Wybierz model</option>

                                        @foreach ($models as $model)
                                            <option value="{{ $model->id }}">
                                                {{ $model->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <label class="form-label">Kolor</label>
                                    <input type="text" name="color" class="form-control"
                                        value="{{ old('color') }}" required>
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

                                <div class="col-12">
                                    <label class="form-label">Zdjęcia</label>
                                    <input type="file" name="images[]" class="form-control" multiple>
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
                                <form method="POST" action=" {{ route('listings.store') }} " enctype="multipart/form-data">

                                    @csrf

                                    <div class="col-12 mt-4">
                                        <button class="btn btn-primary px-4">
                                            Dodaj ogłoszenie
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
