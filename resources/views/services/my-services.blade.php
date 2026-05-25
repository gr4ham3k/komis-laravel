@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Moje usługi</h1>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tytuł</th>
                        <th>Cena</th>
                        <th>Miasto</th>
                        <th>Status</th>
                        <th class="text-end">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->title }}</td>
                            <td>{{ number_format($service->price, 2, ',', ' ') }} PLN</td>
                            <td>{{ $service->city }}</td>
                            <td>{{ $service->status }}</td>
                            <td class="text-end">
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-outline-primary">Szczegóły</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Brak usług.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $services->links() }}</div>
</div>
@endsection
