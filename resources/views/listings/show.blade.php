@extends('layouts.app')
@section('content')

    <div class="container my-4">

        <div class="row">

            <div class="col-md-8">

                <div id="carouselExample" class="carousel slide mb-3">
                    <div class="carousel-inner">

                        @foreach ($listing->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/listings/' . $image->file_name) }}" class="d-block w-100"
                                    style="height: 450px; object-fit: cover;">
                            </div>
                        @endforeach

                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                @if ($listing->images->count() === 0)
                    <div class="mb-3 text-start">
                        <a href="{{ route('listings.images.create', $listing->id) }}" class="btn btn-primary">
                            ➕ Dodaj zdjęcia
                        </a>
                    </div>
                @endif

                <div class="mb-2">
                    @foreach ($listing->tags as $tag)
                        <span class="badge bg-dark">#{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Opis</h5>
                        <p>{{ $listing->description }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <h5>Specyfikacja</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <p>🚗 Marka pojazdu: {{ $listing->brand->name }}</p>
                                <p>🚙 Model pojazdu: {{ $listing->carModel->name }}</p>
                                <p>🚘 Typ nadwozia: {{ $listing->bodyType->name }}</p>
                                <p>📍 Miasto: {{ $listing->city }}</p>
                                <p>📅 Rok: {{ $listing->year }}</p>
                                <p>🛣️ Przebieg: {{ $listing->mileage }} km</p>

                            </div>

                            <div class="col-md-6">
                                <p>⚙️ Skrzynia biegów: {{ $listing->transmission->name }}</p>
                                <p>💪 Moc: {{ $listing->power_hp }} KM</p>
                                <p>🔧 Pojemność silnika: {{ $listing->engine_capacity }}</p>
                                <p>🎨 Kolor: {{ $listing->color }}</p>
                                <p>⛽ Paliwo: {{ $listing->fuel->name }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mt-3 mb-3">
                    <div class="card-body">
                        <h5>Lokalizacja</h5>

                        <iframe width="100%" height="300" style="border:0" loading="lazy" allowfullscreen
                            src="https://www.google.com/maps?q={{ urlencode($listing->city) }}&output=embed">;
                        </iframe>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card mb-3">
                    <div class="card-body">

                        <p>
                            Status:
                            @if ($listing->status === 'active')
                                <span class="badge bg-success">Aktywne</span>
                            @else
                                <span class="badge bg-secondary">Nieaktywne</span>
                            @endif
                        </p>

                        <h3 class="mb-1">{{ $listing->title }}</h3>

                        <h4 class="text-success">
                            {{ $listing->price }} PLN
                        </h4>

                        <hr>

                        <p><strong>Sprzedający:</strong> {{ $listing->user->name }}</p>

                        <div class="mt-3">
                            <form method="get" action="{{ route('conversations.start',$listing) }}">
                                @csrf
                                <button class="btn btn-primary w-100">Napisz do sprzedającego</button>
                            </form>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
