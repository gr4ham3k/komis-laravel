@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1 class="h5 mb-0">Edytuj usluge</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('services.update', $service->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Tytul</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $service->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Opis</label>
                            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Cena (PLN)</label>
                                <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $service->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">Miasto</label>
                                <input id="city" type="text" name="city" value="{{ old('city', $service->city) }}" class="form-control @error('city') is-invalid @enderror" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Zapisz zmiany</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
