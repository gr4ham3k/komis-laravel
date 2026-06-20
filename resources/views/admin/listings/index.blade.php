@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <h2 class="mb-0">Panel admina - Ogłoszenia</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-users"></i> Użytkownicy
            </a>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-wrench"></i> Usługi
            </a>
            <a href="{{ route('admin.dictionaries.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-book"></i> Słowniki
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.listings.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Szukaj ogłoszenia (tytuł, miasto, użytkownik...)" value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Szukaj</button>
            @if(request('search'))
                <a href="{{ route('admin.listings.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Wyczyść</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tytuł</th>
                        <th>Użytkownik</th>
                        <th>Marka / Model</th>
                        <th>Cena</th>
                        <th>Status</th>
                        <th>Dodano</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listings as $listing)
                        <tr>
                            <td class="fw-semibold">{{ $listing->title }}</td>
                            <td>{{ $listing->user->name }}</td>
                            <td>{{ $listing->brand->name }} {{ $listing->carModel->name }}</td>
                            <td>{{ number_format($listing->price, 0, ',', ' ') }} PLN</td>
                            <td>
                                @if ($listing->status === 'active')
                                    <span class="badge bg-success">Aktywne</span>
                                @elseif ($listing->status === 'inactive')
                                    <span class="badge bg-warning text-dark">Nieaktywne</span>
                                @else
                                    <span class="badge bg-secondary">Sprzedane</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $listing->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.listings.edit', $listing->id) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.listings.destroy', $listing->id) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Na pewno usunąć ogłoszenie {{ $listing->title }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Usuń">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Brak ogłoszeń.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $listings->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
