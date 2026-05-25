@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-4">Edytuj ogłoszenie</h1>

                    <form method="POST" action="{{ route('my.listings.update', $listing->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('listings.partials.form-fields', ['listing' => $listing])
                        <button class="btn btn-warning mt-3">Zapisz zmiany</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
