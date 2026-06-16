@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-7 col-xl-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="badge text-bg-dark px-3 py-2 mb-3">MotoKomis</div>
                            <h1 class="h3 mb-2">Rejestracja</h1>
                            <p class="text-muted mb-0">Utwórz konto i wróć na stronę jako zalogowany użytkownik.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nazwa użytkownika</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control form-control-lg"
                                    placeholder="Nazwa użytkownika"
                                    value="{{ old('name') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control form-control-lg"
                                    placeholder="Email"
                                    value="{{ old('email') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hasło</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    placeholder="Hasło"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Powtórz hasło</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control form-control-lg"
                                    placeholder="Powtórz hasło"
                                    required
                                >
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Zarejestruj się</button>
                            </div>

                            <p class="text-center text-muted mt-4 mb-0">
                                Masz już konto?
                                <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Logowanie</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
