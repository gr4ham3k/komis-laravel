@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h1 class="h5 mb-0">Logowanie</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Haslo</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                            <label class="form-check-label" for="remember">Zapamietaj mnie</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-3 mb-0">
                <strong>Konta testowe:</strong><br>
                Admin: <code>admin@komis.test</code> / <code>password</code><br>
                Uzytkownik: <code>user@komis.test</code> / <code>password</code>
            </div>
        </div>
    </div>
</div>
@endsection
