@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2 class="mb-2">Dodaj zdjęcia do ogłoszenia</h2>

            <p class="text-muted mb-4">
                Ogłoszenie: <strong>{{ $listing->title }}</strong>
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('listings.images.store', $listing->id) }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Wybierz zdjęcia
                            </label>

                            <input type="file"
                                   name="images[]"
                                   multiple
                                   accept="image/*"
                                   class="form-control">
                        </div>

                        <small class="text-muted d-block mb-3">
                            Możesz wybrać kilka zdjęć jednocześnie (JPG, PNG, WEBP).
                        </small>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('listings.show', $listing->id) }}"
                               class="btn btn-outline-secondary">
                                Pomiń
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                Zapisz zdjęcia
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection
