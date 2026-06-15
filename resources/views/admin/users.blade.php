@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <h2 class="mb-0">Panel admina - Użytkownicy</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-wrench"></i> Usługi
            </a>
            <a href="{{ route('admin.dictionaries.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-book"></i> Słowniki
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus"></i> Dodaj użytkownika
            </button>
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

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nazwa</th>
                        <th>Email</th>
                        <th>Rola</th>
                        <th>Status</th>
                        <th>Ogłoszenia</th>
                        <th>Rejestracja</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->is_admin)
                                    <span class="badge bg-dark">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Użytkownik</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->is_banned)
                                    <span class="badge bg-danger">Zbanowany</span>
                                @else
                                    <span class="badge bg-success">Aktywny</span>
                                @endif
                            </td>
                            <td>{{ $user->listings_count }}</td>
                            <td class="text-muted small">{{ $user->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal-{{ $user->id }}" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="POST" action="{{ route('admin.users.toggleBan', $user->id) }}" class="d-inline">
                                        @csrf
                                        @if ($user->is_banned)
                                            <button class="btn btn-sm btn-outline-success" title="Odbanuj">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-warning" title="Zbanuj"
                                                onclick="return confirm('Zbanować użytkownika {{ $user->name }}?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </form>

                                    @if ($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Na pewno usunąć użytkownika {{ $user->name }}? Wszystkie jego dane zostaną usunięte.')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Usuń">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Brak użytkowników.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>

{{-- Modal tworzenia --}}
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Dodaj użytkownika</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nazwa</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasło</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_admin" class="form-check-input" id="createIsAdmin">
                        <label class="form-check-label" for="createIsAdmin">Administrator</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modale edycji --}}
@foreach ($users as $user)
    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf @method('PATCH')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">Edytuj użytkownika</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nazwa</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nowe hasło (pozostaw puste, aby nie zmieniać)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_admin" class="form-check-input" id="editIsAdmin-{{ $user->id }}"
                                {{ $user->is_admin ? 'checked' : '' }}>
                            <label class="form-check-label" for="editIsAdmin-{{ $user->id }}">Administrator</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-success">Zapisz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
