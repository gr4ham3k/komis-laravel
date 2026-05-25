@extends('layouts.site')

@section('title', 'Dodaj ogloszenie')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3">Dodaj nowe ogloszenie</h1>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('listings.store') }}" class="row g-3">
                        @csrf

                        <div class="col-md-8">
                            <label class="form-label">Tytul</label>
                            <input class="form-control" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Cena</label>
                            <input class="form-control" type="number" min="0" name="price" value="{{ old('price') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Marka ID</label>
                            <input class="form-control" type="number" min="1" name="brand_id" value="{{ old('brand_id') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Model ID</label>
                            <input class="form-control" type="number" min="1" name="model_id" value="{{ old('model_id') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Paliwo ID</label>
                            <input class="form-control" type="number" min="1" name="fuel_id" value="{{ old('fuel_id') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Skrzynia ID</label>
                            <input class="form-control" type="number" min="1" name="transmission_id" value="{{ old('transmission_id') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Typ nadwozia ID</label>
                            <input class="form-control" type="number" min="1" name="body_type_id" value="{{ old('body_type_id') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Miasto</label>
                            <input class="form-control" name="city" value="{{ old('city') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kolor</label>
                            <input class="form-control" name="color" value="{{ old('color') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Rok</label>
                            <input class="form-control" type="number" min="1950" name="year" value="{{ old('year') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Przebieg</label>
                            <input class="form-control" type="number" min="0" name="mileage" value="{{ old('mileage') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Pojemnosc silnika</label>
                            <input class="form-control" type="number" min="0" name="engine_capacity" value="{{ old('engine_capacity') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Moc (KM)</label>
                            <input class="form-control" type="number" min="0" name="power_hp" value="{{ old('power_hp') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Opis</label>
                            <textarea class="form-control" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">Dodaj ogloszenie</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
