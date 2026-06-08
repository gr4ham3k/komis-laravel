{{-- resources/views/services/show.blade.php --}}
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>{{ $service->title }} - Serwis motoryzacyjny</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-8">
                @if ($service->images->isNotEmpty())
                    <div id="serviceCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($service->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/services/' . $image->file_name) }}" class="d-block w-100"
                                        style="height: 450px; object-fit: cover; border-radius: 6px;">
                                </div>
                            @endforeach
                        </div>
                        @if ($service->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Poprzednie</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Następne</span>
                            </button>
                        @endif
                    </div>
                @else
                    @auth
                        @if(Auth::id() === $service->user_id)
                            <div class="mb-3 text-start">
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary">
                                    ➕ Dodaj zdjęcia
                                </a>
                            </div>
                        @endif
                    @endauth
                @endif

                <!-- Service Details -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h2>{{ $service->title }}</h2>

                        <!-- Rating Stars -->
                        <div class="mb-2">
                            @php
                                $avgRating = $service->averageRating();
                                $fullStars = floor($avgRating);
                                $hasHalf = $avgRating - $fullStars >= 0.5;
                            @endphp

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $fullStars)
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i == $fullStars + 1 && $hasHalf)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="ms-2 text-muted">({{ $service->reviews->count() }} opinii)</span>
                        </div>

                        <hr>

                        <h5>Opis usługi</h5>
                        <p>{{ $service->description }}</p>

                        <hr>

                        <h5>Szczegóły</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="fas fa-map-marker-alt"></i> <strong>Miasto:</strong> {{ $service->city }}
                                </p>
                                <p><i class="fas fa-eye"></i> <strong>Wyświetleń:</strong> {{ $service->views_count }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-calendar"></i> <strong>Dodano:</strong>
                                    {{ $service->created_at->format('d.m.Y') }}</p>
                                <p><i class="fas fa-user"></i> <strong>Wystawiający:</strong> {{ $service->user->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Lokalizacja</h5>

                        <iframe width="100%" height="300" style="border:0" loading="lazy" allowfullscreen
                            src="https://www.google.com/maps?q={{ urlencode($service->city) }}&output=embed">
                        </iframe>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="card">
                    <div class="card-body">
                        <h4>Opinie klientów</h4>

                        <form method="POST" action="{{ route('services.review', $service->id) }}" class="mb-4">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Ocena</label>
                                <select name="rating" class="form-select" required>
                                    <option value="5">★★★★★ (5)</option>
                                    <option value="4">★★★★☆ (4)</option>
                                    <option value="3">★★★☆☆ (3)</option>
                                    <option value="2">★★☆☆☆ (2)</option>
                                    <option value="1">★☆☆☆☆ (1)</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Komentarz</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Twoja opinia..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Dodaj opinię</button>
                        </form>
                        <hr>

                        @forelse($service->reviews as $review)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $review->user->name }}</strong>
                                    <small class="text-muted">{{ $review->created_at->format('d.m.Y') }}</small>
                                </div>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="mt-1">{{ $review->comment }}</p>
                            </div>
                            @if (!$loop->last)
                                <hr>
                            @endif
                        @empty
                            <p class="text-muted">Brak opinii. Bądź pierwszy!</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Price and Contact Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="text-success mb-1">{{ number_format($service->price, 2) }} PLN</h3>
                        <hr>
                        <div class="d-grid gap-2">
                            <form method="get" action="{{ route('conversations.start.service', $service->id) }}">
                                @csrf
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-envelope"></i> Napisz do usługodawcy
                                </button>
                            </form>

                            @auth
                                @if(Auth::id() === $service->user_id)
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning text-white">
                                        <i class="fas fa-edit"></i> Edytuj usługę
                                    </a>
                                @endif
                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-gear"></i> Zarządzaj w panelu admina
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
