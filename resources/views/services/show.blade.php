{{-- resources/views/services/show.blade.php --}}
@extends('layouts.app')

@section('title', $service->title . ' - MotoKomis')

@section('content')
    <section class="section-band py-4">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
                <div>
                    <p class="text-uppercase text-muted fw-semibold small mb-1">Szczegóły usługi</p>
                    <h1 class="h2 mb-1">{{ $service->title }}</h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $service->city }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('services.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-1"></i> Wszystkie usługi
                    </a>
                    <a href="{{ route('services.create') }}" class="btn btn-mk">
                        <i class="fas fa-plus me-1"></i> Dodaj usługę
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="filter-panel p-4 mb-3">
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

                    <h2 class="h5">Opis usługi</h2>
                    <p>{{ $service->description }}</p>

                    <hr>

                    <h2 class="h5">Szczegóły</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-map-marker-alt me-1"></i> <strong>Miasto:</strong> {{ $service->city }}</p>
                            <p><i class="fas fa-eye me-1"></i> <strong>Wyświetleń:</strong> {{ $service->views_count }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="fas fa-calendar me-1"></i> <strong>Dodano:</strong> {{ $service->created_at->format('d.m.Y') }}</p>
                            <p><i class="fas fa-user me-1"></i> <strong>Wystawiający:</strong> {{ $service->user->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="filter-panel p-4">
                    <h2 class="h4">Opinie klientów</h2>

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
                        <button type="submit" class="btn btn-mk">Dodaj opinię</button>
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

            <aside class="col-lg-4">
                <div class="filter-panel p-4 position-sticky" style="top: 88px;">
                    <h2 class="h3 text-success mb-1">{{ number_format($service->price, 2) }} PLN</h2>
                    <hr>
                    <div class="d-grid gap-2">
                        <a class="btn btn-mk" href="mailto:{{ $service->user->email }}">
                            <i class="fas fa-envelope me-1"></i> Napisz do usługodawcy
                        </a>

                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edytuj
                        </a>

                        <form method="POST" action="{{ route('services.destroy', $service->id) }}"
                            onsubmit="return confirm('Czy na pewno chcesz usunąć tę usługę?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-1"></i> Usuń
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
