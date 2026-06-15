@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Panel użytkownika</h1>
            <p class="text-muted mb-0">Witaj, {{ Auth::user()->name }}</p>
        </div>
        <a href="{{ route('listings.create') }}" class="btn btn-mk">
            <i class="fas fa-plus me-1"></i> Dodaj ogłoszenie
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="filter-panel p-3 text-center">
                <div class="fs-2 fw-bold text-primary">{{ $stats['listings_count'] }}</div>
                <div class="text-muted small">Wszystkie ogłoszenia</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="filter-panel p-3 text-center">
                <div class="fs-2 fw-bold text-success">{{ $stats['active_listings'] }}</div>
                <div class="text-muted small">Aktywne ogłoszenia</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="filter-panel p-3 text-center">
                <div class="fs-2 fw-bold text-info">{{ $stats['services_count'] }}</div>
                <div class="text-muted small">Usługi</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="filter-panel p-3 text-center">
                <div class="fs-2 fw-bold text-warning">{{ $stats['total_views'] }}</div>
                <div class="text-muted small">Wyświetlenia</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="filter-panel p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Ostatnie ogłoszenia</h2>
                    <a href="{{ route('my.listings') }}" class="btn btn-outline-dark btn-sm">Zobacz wszystkie</a>
                </div>

                @if ($recentListings->isEmpty())
                    <p class="text-muted mb-0">Nie masz jeszcze żadnych ogłoszeń.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tytuł</th>
                                    <th>Status</th>
                                    <th>Cena</th>
                                    <th>Wyświetlenia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentListings as $listing)
                                    <tr>
                                        <td>
                                            <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none fw-semibold">
                                                {{ $listing->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($listing->status === 'active')
                                                <span class="badge bg-success">Aktywne</span>
                                            @else
                                                <span class="badge bg-secondary">Nieaktywne</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($listing->price, 0, ',', ' ') }} PLN</td>
                                        <td>{{ $listing->views_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="filter-panel p-4">
                <h2 class="h5 mb-3">Szybkie akcje</h2>
                <div class="d-grid gap-2">
                    <a href="{{ route('listings.create') }}" class="btn btn-mk">
                        <i class="fas fa-plus me-1"></i> Dodaj ogłoszenie
                    </a>
                    <a href="{{ route('my.listings') }}" class="btn btn-outline-dark">
                        <i class="fas fa-list me-1"></i> Moje ogłoszenia
                    </a>
                    <a href="{{ route('services.create') }}" class="btn btn-outline-dark">
                        <i class="fas fa-wrench me-1"></i> Dodaj usługę
                    </a>
                    <a href="{{ route('my.services') }}" class="btn btn-outline-dark">
                        <i class="fas fa-briefcase me-1"></i> Moje usługi
                    </a>
                    <a href="{{ route('conversations.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-envelope me-1"></i> Wiadomości
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
