<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .gradient-custom-3 {
            background: #84fab0;

            background: -webkit-linear-gradient(
                to right,
                rgba(132,250,176,0.5),
                rgba(143,211,244,0.5)
            );

            background: linear-gradient(
                to right,
                rgba(132,250,176,0.5),
                rgba(143,211,244,0.5)
            );
        }

        .gradient-custom-4 {
            background: #84fab0;

            background: -webkit-linear-gradient(
                to right,
                rgba(132,250,176,1),
                rgba(143,211,244,1)
            );

            background: linear-gradient(
                to right,
                rgba(132,250,176,1),
                rgba(143,211,244,1)
            );
        }

        body{
            min-height:100vh;
        }

        .card{
            border-radius:20px;
        }

        .form-control{
            padding:12px;
        }
    </style>
</head>
<body>

<section class="vh-100 gradient-custom-3">

    <div class="container h-100">

        <div class="row d-flex justify-content-center align-items-center h-100">

            <div class="col-md-8 col-lg-6 col-xl-5">

                <div class="card shadow-lg">

                    <div class="card-body p-5">

                        <h2 class="text-uppercase text-center mb-4">
                            Login
                        </h2>

                        <p class="text-center text-muted mb-4">
                            Sign in to your account
                        </p>


                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif


                        <!-- Validation errors -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <form action="/login" method="POST">
                            @csrf

                            <div class="mb-4">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Enter email"
                                    value="{{ old('email') }}"
                                    required
                                >

                            </div>


                            <div class="mb-4">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Enter password"
                                    required
                                >

                            </div>


                            <div class="d-grid">

                                <button
                                    type="submit"
                                    class="btn btn-lg text-white gradient-custom-4"
                                >
                                    Login
                                </button>

                            </div>


                            <p class="text-center text-muted mt-4 mb-0">

                                Don't have an account?

                                <a href="/register"
                                   class="fw-bold text-decoration-none">
                                    Register
                                </a>

                            </p>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

</body>
</html>
