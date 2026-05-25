@extends('layouts.site')

@section('title', 'Panel admina - uzytkownicy')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Zarzadzanie uzytkownikami</h1>
        <span class="text-secondary">Liczba: {{ $users->total() }}</span>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2">
                <div class="col-md-5">
                    <input
                        class="form-control"
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Szukaj po imieniu lub emailu"
                    >
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="role">
                        <option value="">Wszystkie role</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        <option value="user" @selected(request('role') === 'user')>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Wszystkie statusy</option>
                        <option value="active" @selected(request('status') === 'active')>Aktywny</option>
                        <option value="banned" @selected(request('status') === 'banned')>Zbanowany</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filtruj</button>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('admin.users.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Uzytkownik</th>
                        <th>Email</th>
                        <th>Rola</th>
                        <th>Ogloszenia</th>
                        <th>Status</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->is_admin)
                                    <span class="badge text-bg-primary">Admin</span>
                                @else
                                    <span class="badge text-bg-secondary">User</span>
                                @endif
                            </td>
                            <td>{{ $user->listings_count }}</td>
                            <td>
                                @if ($user->is_banned)
                                    <span class="badge text-bg-danger">Zbanowany</span>
                                @else
                                    <span class="badge text-bg-success">Aktywny</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 flex-wrap justify-content-end">
                                    <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_banned" value="{{ $user->is_banned ? 0 : 1 }}">
                                        <button class="btn btn-sm {{ $user->is_banned ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                            {{ $user->is_banned ? 'Odbanuj' : 'Zbanuj' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_admin" value="{{ $user->is_admin ? 0 : 1 }}">
                                        <button class="btn btn-sm {{ $user->is_admin ? 'btn-outline-warning' : 'btn-outline-primary' }}">
                                            {{ $user->is_admin ? 'Odbierz admina' : 'Zrob adminem' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Na pewno usunac to konto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-dark">Usun konto</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">Brak uzytkownikow.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
