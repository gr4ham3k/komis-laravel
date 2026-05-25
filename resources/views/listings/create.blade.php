@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-4">Dodaj ogłoszenie</h1>

                    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
                        @csrf
                        @include('listings.partials.form-fields', ['listing' => null])
                        <button class="btn btn-primary mt-3">Dodaj ogłoszenie</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
