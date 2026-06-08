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
                            <p class="text-muted mb-0">Utworz konto i wroc na strone jako zalogowany uzytkownik.</p>
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
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control form-control-lg"
                                    placeholder="Enter your name"
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
                                    placeholder="Enter your email"
                                    value="{{ old('email') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control form-control-lg"
                                    placeholder="Enter password"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Repeat Password</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control form-control-lg"
                                    placeholder="Repeat password"
                                    required
                                >
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Register</button>
                            </div>

                            <p class="text-center text-muted mt-4 mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
