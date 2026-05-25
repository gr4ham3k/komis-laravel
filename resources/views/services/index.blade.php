@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Uslugi motoryzacyjne</h1>
        @auth
            <a href="{{ route('services.create') }}" class="btn btn-primary">Dodaj usluge</a>
        @endauth
    </div>

    <div class="row g-3">
        @forelse($services as $service)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5">{{ $service->title }}</h2>
                        <p class="text-muted mb-2">{{ $service->city }}</p>
                        <p class="mb-2">{{ \Illuminate\Support\Str::limit($service->description, 120) }}</p>
                        <p class="fw-bold text-success mb-3">{{ number_format((float) $service->price, 2, ',', ' ') }} PLN</p>

                        @php $avgRating = $service->averageRating(); @endphp
                        <div class="small text-muted mb-3">
                            Ocena: {{ number_format($avgRating, 1) }}/5 ({{ $service->reviews->count() }} opinii)
                        </div>

                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-primary">Szczegoly</a>
                    </div>
                    <div class="card-footer text-muted small">
                        Dodano: {{ $service->created_at->format('d.m.Y') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">Brak dostepnych uslug.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
@endsection
