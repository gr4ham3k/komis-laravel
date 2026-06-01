@extends('layouts.app')

@section('title', 'Dodaj zdjęcia - MotoKomis')

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Zdjęcia ogłoszenia</p>
                    <h1 class="h2 mb-1">Dodaj zdjęcia</h1>
                    <p class="text-muted mb-0">Ogłoszenie: <strong>{{ $listing->title }}</strong></p>
                </div>
                <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-1"></i> Wróć do ogłoszenia
                </a>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="filter-panel p-4">
                    <form action="{{ route('listings.images.store', $listing->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Wybierz zdjęcia</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                        </div>

                        <small class="text-muted d-block mb-3">
                            Możesz wybrać kilka zdjęć jednocześnie (JPG, PNG, WEBP).
                        </small>

                        <div class="d-flex justify-content-between gap-2">
                            <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-outline-secondary">
                                Pomiń
                            </a>

                            <button type="submit" class="btn btn-mk">
                                Zapisz zdjęcia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
