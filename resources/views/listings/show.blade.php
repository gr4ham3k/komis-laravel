<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Ogłoszenie</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container my-4">

        <div class="row">

            <div class="col-md-8">

                <div id="carouselExample" class="carousel slide mb-3">
                    <div class="carousel-inner">

                        @foreach ($listing->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('images/' . $image->file_name) }}" class="d-block w-100"
                                    alt="{{ $image->original_name }}">
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

                <div class="mb-2">
                    <span class="badge bg-dark">#SUV</span>
                    <span class="badge bg-dark">#Diesel</span>
                    <span class="badge bg-dark">#Bezwypadkowy</span>
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
                                <p>📍 Miasto: {{ $listing->city }}</p>
                                <p>📅 Rok: {{ $listing->year }}</p>
                                <p>🚗 Przebieg: {{ $listing->mileage }} km</p>
                            </div>

                            <div class="col-md-6">
                                <p>⚙️ Moc: {{ $listing->power_hp }} KM</p>
                                <p>🎨 Kolor: {{ $listing->color }}</p>
                                <p>⛽ Paliwo: {{ $listing->fuel->name }}</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card mb-3">
                    <div class="card-body">

                        <h3 class="mb-1">{{ $listing->title }}</h3>

                        <h4 class="text-success">
                            {{ $listing->price }} PLN
                        </h4>

                        <hr>

                        <p><strong>Sprzedający:</strong> {{ $listing->user->name }}</p>

                        <div class="mt-3">
                            <button class="btn btn-primary w-100">Napisz do sprzedającego</button>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
