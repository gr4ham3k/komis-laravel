{{-- resources/views/services/index.blade.php --}}
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Usługi motoryzacyjne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container my-4">
    <h1 class="mb-4">Usługi motoryzacyjne</h1>
    
    <div class="row">
        @forelse($services as $service)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->title }}</h5>
                        <h6 class="text-success">{{ number_format($service->price, 2) }} PLN</h6>
                        <p class="card-text text-muted">
                            <i class="fas fa-map-marker-alt"></i> {{ $service->city }}
                        </p>
                        <p class="card-text">{{ Str::limit($service->description, 100) }}</p>
                        
                        <!-- Rating -->
                        <div class="mb-2">
                            @php $avgRating = $service->averageRating(); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="small text-muted">({{ $service->reviews->count() }})</span>
                        </div>
                        
                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-primary">Zobacz więcej</a>
                    </div>
                    <div class="card-footer text-muted">
                        <small>Dodano: {{ $service->created_at->format('d.m.Y') }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Brak dostępnych usług.</div>
            </div>
        @endforelse
    </div>
    
    {{ $services->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>