@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Moje ogłoszenia</h1>
            <p class="text-muted mb-0">Zarządzaj swoimi ogłoszeniami</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('user.panel') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-1"></i> Panel
            </a>
            <a href="{{ route('listings.create') }}" class="btn btn-mk">
                <i class="fas fa-plus me-1"></i> Dodaj ogłoszenie
            </a>
        </div>
    </div>

    @if ($listings->isEmpty())
        <div class="filter-panel p-5 text-center">
            <i class="fas fa-car fa-3x text-muted mb-3"></i>
            <h2 class="h5">Nie masz jeszcze żadnych ogłoszeń</h2>
            <p class="text-muted">Dodaj swoje pierwsze ogłoszenie już teraz!</p>
            <a href="{{ route('listings.create') }}" class="btn btn-mk">
                <i class="fas fa-plus me-1"></i> Dodaj ogłoszenie
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle filter-panel mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Zdjęcie</th>
                        <th>Tytuł</th>
                        <th>Marka / Model</th>
                        <th>Cena</th>
                        <th>Status</th>
                        <th>Wyświetlenia</th>
                        <th>Dodano</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listings as $listing)
                        <tr>
                            <td style="width: 80px;">
                                @if ($listing->images->first())
                                    <img src="{{ asset('storage/' . $listing->images->first()->file_name) }}"
                                         class="rounded"
                                         style="width: 60px; height: 40px; object-fit: cover;"
                                         alt="">
                                @else
                                    <div class="rounded bg-secondary d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 40px;">
                                        <i class="fas fa-camera text-white small"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('listings.show', $listing) }}" class="text-decoration-none fw-semibold">
                                    {{ $listing->title }}
                                </a>
                            </td>
                            <td>{{ $listing->brand?->name }} {{ $listing->carModel?->name }}</td>
                            <td class="fw-semibold">{{ number_format($listing->price, 0, ',', ' ') }} PLN</td>
                            <td>
                                @if ($listing->status === 'active')
                                    <span class="badge bg-success">Aktywne</span>
                                @else
                                    <span class="badge bg-secondary">Nieaktywne</span>
                                @endif
                            </td>
                            <td>{{ $listing->views_count }}</td>
                            <td class="text-muted small">{{ $listing->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('listings.edit', $listing) }}" class="btn btn-sm btn-outline-primary" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('listings.destroy', $listing) }}"
                                          onsubmit="return confirm('Na pewno usunąć to ogłoszenie?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Usuń">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $listings->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
