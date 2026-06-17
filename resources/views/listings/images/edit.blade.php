@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Zdjęcia ogłoszenia</h2>

        <a href="{{ route('listings.edit', $listing) }}"
           class="btn btn-outline-dark">
            Powrót do edycji
        </a>
    </div>

    {{-- ZDJĘCIA --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            @if($listing->images->count())
                <div class="row g-3">
                    @foreach ($listing->images as $image)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card h-100 border-0 shadow-sm">

                                <img src="{{ asset('storage/' . $image->file_name) }}"
                                     class="card-img-top"
                                     style="height: 160px; object-fit: cover;">

                                <div class="card-body p-2 text-center">

                                    <form method="POST"
                                          action="{{ route('images.destroy', $image) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger w-100">
                                            Usuń
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    Brak zdjęć dla tego ogłoszenia
                </div>
            @endif

        </div>
    </div>

    {{-- UPLOAD --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="mb-3">Dodaj nowe zdjęcia</h5>

            <form method="POST"
                  action="{{ route('listings.images.store', $listing) }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="file"
                       name="images[]"
                       multiple
                       accept="image/*"
                       class="form-control">

                <div class="mt-3 d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Dodaj zdjęcia
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
