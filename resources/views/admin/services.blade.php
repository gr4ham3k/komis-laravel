<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Usługi - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            width: 48px;
            height: 48px;
            background: #e8edf3;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #66788a;
            font-size: 20px;
            margin-right: 12px;
        }

        .service-info-cell {
            display: flex;
            align-items: center;
        }

        @media (max-width: 767.98px) {
            .table-responsive {
                border-radius: 6px;
            }
            
            .service-description {
                max-width: 220px;
            }
        }
    </style>
</head>
<body>

<div class="container services-shell my-4">
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-start align-items-md-center">
            <div>
                <h1 class="h3 mb-1">Panel admina - Usługi</h1>
                <p class="text-muted mb-0">Zarządzanie usługami motoryzacyjnymi</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.dictionaries.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-book"></i> Słowniki
                </a>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createServiceModal">
                    <i class="fas fa-plus"></i> Dodaj usługę
                </button>
            </div>
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
                        <tr>
                            <td>
                                <div class="service-info-cell">
                                    <div class="service-thumb-small">
                                        <i class="fas fa-screwdriver-wrench"></i>
                                    </div>
                                    <div>
                                        <div class="service-title">{{ $service->title }}</div>
                                        <div class="service-description">
                                            {{ Str::limit($service->description, 90) }}
                                        </div>
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

<!-- Modal Dodawania -->
<div class="modal fade" id="createServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.services.store') }}">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Dodaj usługę
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

<!-- Modale Edycji i Usuwania -->
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
                            Edytuj usługę
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-0">
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
                            <strong><i class="fas fa-wrench me-2"></i> {{ $service->title }}</strong>
                            <div class="text-muted small mt-1">{{ $service->city }} • {{ number_format($service->price, 2) }} PLN</div>
                        </div>
                        <p class="text-danger small mb-0">Ta operacja jest nieodwracalna.</p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>