@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Moje usługi</h1>
            <p class="text-muted mb-0">Zarządzaj swoimi usługami</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('user.panel') }}" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-1"></i> Panel
            </a>
            <a href="{{ route('services.create') }}" class="btn btn-mk">
                <i class="fas fa-plus me-1"></i> Dodaj usługę
            </a>
        </div>
    </div>

    @if ($services->isEmpty())
        <div class="filter-panel p-5 text-center">
            <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
            <h2 class="h5">Nie masz jeszcze żadnych usług</h2>
            <p class="text-muted">Dodaj swoją pierwszą usługę już teraz!</p>
            <a href="{{ route('services.create') }}" class="btn btn-mk">
                <i class="fas fa-plus me-1"></i> Dodaj usługę
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle filter-panel mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tytuł</th>
                        <th>Cena</th>
                        <th>Miasto</th>
                        <th>Status</th>
                        <th>Wyświetlenia</th>
                        <th>Dodano</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>
                                <a href="{{ route('services.show', $service->id) }}" class="text-decoration-none fw-semibold">
                                    {{ $service->title }}
                                </a>
                            </td>
                            <td class="fw-semibold">{{ number_format($service->price, 0, ',', ' ') }} PLN</td>
                            <td>{{ $service->city }}</td>
                            <td>
                                @if ($service->status === 'active')
                                    <span class="badge bg-success">Aktywne</span>
                                @else
                                    <span class="badge bg-secondary">Nieaktywne</span>
                                @endif
                            </td>
                            <td>{{ $service->views_count }}</td>
                            <td class="text-muted small">{{ $service->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('services.destroy', $service->id) }}"
                                          onsubmit="return confirm('Na pewno usunąć tę usługę?')">
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
            {{ $services->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
