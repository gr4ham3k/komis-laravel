{{-- resources/views/services/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Dodaj usługę - MotoKomis')

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Nowa oferta</p>
                    <h1 class="h2 mb-1">Dodaj nową usługę</h1>
                    <p class="text-muted mb-0">Uzupełnij dane usługi, żeby była widoczna na stronie usług.</p>
                </div>
                <a href="{{ route('services.index') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-1"></i> Wróć do usług
                </a>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6">
                <div class="filter-panel p-4">
                    <form method="POST" action="{{ route('services.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tytuł usługi</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Opis</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cena (PLN)</label>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Miasto</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-mk w-100">Dodaj usługę</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
