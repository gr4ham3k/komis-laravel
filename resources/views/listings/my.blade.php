@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Moje ogłoszenia</h1>
        <a class="btn btn-primary" href="{{ route('listings.create') }}">Dodaj ogłoszenie</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tytuł</th>
                        <th>Cena</th>
                        <th>Miasto</th>
                        <th>Wyświetlenia</th>
                        <th>Status</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($listings as $listing)
                    <tr>
                        <td>{{ $listing->title }}</td>
                        <td>{{ number_format($listing->price, 0, ',', ' ') }} PLN</td>
                        <td>{{ $listing->city }}</td>
                        <td>{{ $listing->views_count }}</td>
                        <td>
                            @if($listing->status === 'active')
                                <span class="badge bg-success">Aktywne</span>
                            @else
                                <span class="badge bg-secondary">Nieaktywne</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-sm btn-outline-primary">Podgląd</a>
                            <a href="{{ route('my.listings.edit', $listing->id) }}" class="btn btn-sm btn-outline-warning">Edytuj</a>
                            <form action="{{ route('my.listings.destroy', $listing->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Na pewno usunąć to ogłoszenie?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Brak ogłoszeń.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $listings->links() }}</div>
</div>
@endsection
