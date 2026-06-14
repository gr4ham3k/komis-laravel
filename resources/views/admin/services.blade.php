@extends('layouts.app')

@push('styles')
    <style>
        body {
            background: #f4f6f8;
            color: #1f2933;
        }

        .services-shell {
            max-width: 1120px;
        }

        .admin-panel,
        .search-panel {
            background: #ffffff;
            border: 1px solid #d8dee6;
            border-radius: 6px;
        }

        .search-panel {
            padding: 18px;
        }

        .admin-panel {
            padding: 0;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 16px 14px;
            border-color: #e9ecef;
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            color: #1f2933;
            border-bottom-width: 1px;
        }

        .service-description {
            max-width: 380px;
            font-size: 0.85rem;
            color: #66788a;
            margin-top: 4px;
        }

        .service-title {
            font-weight: 600;
            color: #1f2933;
        }

        .badge {
            padding: 5px 10px;
            font-weight: 500;
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 0.8rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .service-thumb-small {
            width: 60px;
            height: 60px;
            background: #e8edf3;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-right: 12px;
            flex-shrink: 0;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .service-thumb-small:hover {
            transform: scale(1.05);
        }

        .service-thumb-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .service-thumb-small i {
            font-size: 24px;
            color: #66788a;
        }

        .service-info-cell {
            display: flex;
            align-items: center;
        }

        .service-images-preview {
            display: flex;
            gap: 5px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .service-images-preview .image-badge {
            width: 40px;
            height: 40px;
            background: #e8edf3;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .service-images-preview .image-badge:hover {
            transform: scale(1.1);
        }

        .service-images-preview .image-badge img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .service-images-preview .image-badge i {
            font-size: 16px;
            color: #66788a;
        }

        /* Modal dla zdjęć w pełnym rozmiarze */
        .image-modal-content {
            background: rgba(0,0,0,0.9);
        }

        .image-modal-content .modal-content {
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .full-image {
            max-width: 90vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .image-info {
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .image-counter {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        @media (max-width: 767.98px) {
            .table-responsive {
                border-radius: 6px;
            }

            .service-description {
                max-width: 220px;
            }

            .service-thumb-small {
                width: 50px;
                height: 50px;
            }
        }
    </style>
@endpush

@section('content')
<div class="container services-shell py-4">
    <!-- Górny pasek z przyciskami -->
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <h2 class="mb-0">Panel admina - Usługi</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dictionaries.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-book"></i> Słowniki
            </a>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createServiceModal">
                <i class="fas fa-plus"></i> Dodaj usługę
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Popraw błędy w formularzu:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-panel">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%">Usługa</th>
                        <th>Miasto</th>
                        <th>Cena</th>
                        <th>Status</th>
                        <th>Właściciel</th>
                        <th class="text-end" style="width: 180px">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        @php
                            $serviceImages = $service->images;
                            $hasImages = $serviceImages->count() > 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="service-info-cell">
                                    <!-- Główne zdjęcie - tylko do wyświetlania -->
                                    <div class="service-thumb-small"
                                         onclick="showImageGallery({{ $service->id }}, 0)"
                                         data-bs-toggle="tooltip"
                                         title="Kliknij aby zobaczyć zdjęcia">
                                        @if($hasImages)
                                            <img src="{{ asset('storage/services/' . $serviceImages->first()->file_name) }}"
                                                 alt="{{ $service->title }}">
                                        @else
                                            <i class="fas fa-screwdriver-wrench"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="service-title">{{ $service->title }}</div>
                                        <div class="service-description">
                                            {{ Str::limit($service->description, 90) }}
                                        </div>

                                        <!-- Galeria miniatur - TYLKO PODGLĄD -->
                                        @if($hasImages)
                                            <div class="service-images-preview">
                                                <small class="text-muted me-2">
                                                    <i class="fas fa-images"></i> {{ $serviceImages->count() }}
                                                    @if($serviceImages->count() == 1) zdjęcie @else zdjęć @endif
                                                </small>
                                                @foreach($serviceImages->take(4) as $index => $image)
                                                    <div class="image-badge"
                                                         onclick="showImageGallery({{ $service->id }}, {{ $index }})"
                                                         data-bs-toggle="tooltip"
                                                         title="{{ $image->original_name }}">
                                                        <img src="{{ asset('storage/services/' . $image->file_name) }}"
                                                             alt="mini">
                                                    </div>
                                                @endforeach
                                                @if($serviceImages->count() > 4)
                                                    <div class="image-badge bg-light"
                                                         onclick="showImageGallery({{ $service->id }}, 0)"
                                                         style="cursor: pointer;">
                                                        <small>+{{ $serviceImages->count() - 4 }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <small class="text-muted">
                                                <i class="fas fa-image"></i> Brak zdjęć
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt text-muted me-1" style="font-size: 0.8rem;"></i>
                                {{ $service->city }}
                            </td>
                            <td class="fw-semibold" style="color: #108043;">
                                {{ number_format($service->price, 2) }} PLN
                            </td>
                            <td>
                                @if($service->status === 'active')
                                    <span class="badge bg-success">Aktywna</span>
                                @elseif($service->status === 'inactive')
                                    <span class="badge bg-secondary">Nieaktywna</span>
                                @else
                                    <span class="badge bg-danger">Usunięta</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-user text-muted me-1" style="font-size: 0.8rem;"></i>
                                {{ $service->user->name ?? 'Brak użytkownika' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-outline-primary" title="Podgląd">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editServiceModal-{{ $service->id }}" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteServiceModal-{{ $service->id }}" title="Usuń">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-wrench fa-2x mb-3 d-block opacity-50"></i>
                                Brak usług do wyświetlenia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $services->links() }}
    </div>
</div>

<!-- Modal Dodawania (bez zdjęć w tym widoku) -->
<div class="modal fade" id="createServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.services.store') }}">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Dodaj usługę użytkownikowi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-0">
                    @include('admin.partials.service-form', ['service' => null, 'users' => $users])
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Dodaj usługę</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modale Edycji (bez zdjęć) -->
@foreach($services as $service)
    <div class="modal fade" id="editServiceModal-{{ $service->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.services.update', $service->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">
                            <i class="fas fa-edit text-warning me-2"></i>
                            Edytuj usługę: {{ $service->title }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-0">
                        <!-- TYLKO PODGLĄD ZDJĘĆ - bez możliwości edycji -->
                        @if($service->images->count() > 0)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informacja:</strong> Zdjęcia można dodawać/edytować tylko z poziomu panelu użytkownika.
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-images me-1"></i>Zdjęcia usługi (podgląd)
                                </label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($service->images as $image)
                                        <div class="text-center" style="width: 100px;">
                                            <img src="{{ asset('storage/services/' . $image->file_name) }}"
                                                 class="img-thumbnail"
                                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                 onclick="window.open('{{ asset('storage/services/' . $image->file_name) }}', '_blank')"
                                                 data-bs-toggle="tooltip"
                                                 title="Kliknij aby powiększyć">
                                            <small class="text-muted d-block" style="font-size: 10px;">{{ Str::limit($image->original_name, 15) }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                        @endif

                        @include('admin.partials.service-form', ['service' => $service, 'users' => $users])
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal usuwania -->
    <div class="modal fade" id="deleteServiceModal-{{ $service->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-danger">
                            <i class="fas fa-trash-alt me-2"></i>
                            Usuń usługę
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">Czy na pewno chcesz usunąć tę usługę?</p>
                        <div class="alert alert-light border">
                            @if($service->images->first())
                                <img src="{{ asset('storage/services/' . $service->images->first()->file_name) }}"
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; float: left; margin-right: 12px;">
                            @else
                                <i class="fas fa-screwdriver-wrench fa-2x text-muted float-start me-2"></i>
                            @endif
                            <strong>{{ $service->title }}</strong>
                            <div class="text-muted small mt-1">{{ $service->city }} • {{ number_format($service->price, 2) }} PLN</div>
                            <div class="clearfix"></div>
                        </div>
                        <p class="text-danger small mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Usunięte zostaną również wszystkie zdjęcia powiązane z tą usługą.
                        </p>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-danger">Usuń trwale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Globalny modal do galerii zdjęć -->
<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content image-modal-content" style="background: transparent;">
            <div class="modal-body text-center p-0">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index: 1060;"></button>

                <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="galleryCarouselInner">
                        <!-- Dynamicznie wypełniane przez JS -->
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Poprzednie</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Następne</span>
                    </button>
                </div>

                <div id="galleryImageInfo" class="image-info position-absolute bottom-0 start-50 translate-middle-x mb-3">
                    <!-- Dynamiczna info -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Przechowywanie danych galerii
const serviceImages = {!! json_encode($services->mapWithKeys(function($service) {
    return [$service->id => $service->images->map(function($image) {
        return [
            'url' => asset('storage/services/' . $image->file_name),
            'name' => $image->original_name,
            'id' => $image->id
        ];
    })];
})) !!};

function showImageGallery(serviceId, startIndex = 0) {
    const images = serviceImages[serviceId];

    if (!images || images.length === 0) {
        return;
    }

    // Wypełnij karuzelę
    const carouselInner = document.getElementById('galleryCarouselInner');
    carouselInner.innerHTML = '';

    images.forEach((image, index) => {
        const isActive = index === startIndex;
        const div = document.createElement('div');
        div.className = `carousel-item ${isActive ? 'active' : ''}`;
        div.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
                <img src="${image.url}" class="full-image" alt="${image.name}" style="max-width: 90vw; max-height: 75vh; object-fit: contain;">
            </div>
        `;
        carouselInner.appendChild(div);
    });

    // Dodaj info o zdjęciu
    const imageInfo = document.getElementById('galleryImageInfo');
    updateImageInfo(startIndex + 1, images.length, images[startIndex].name);

    // Obsługa zmiany slajdu
    const carousel = document.getElementById('galleryCarousel');
    carousel.addEventListener('slid.bs.carousel', function(event) {
        const newIndex = event.to;
        updateImageInfo(newIndex + 1, images.length, images[newIndex].name);
    });

    // Pokaż modal
    const modal = new bootstrap.Modal(document.getElementById('imageGalleryModal'));
    modal.show();
}

function updateImageInfo(current, total, fileName) {
    const infoDiv = document.getElementById('galleryImageInfo');
    infoDiv.innerHTML = `
        <span>${current} / ${total}</span>
        <span class="mx-2">•</span>
        <span>${fileName}</span>
    `;
}

// Inicjalizacja tooltipów
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
