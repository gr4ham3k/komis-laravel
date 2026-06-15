@extends('layouts.app')

@section('content')
    @php
        $compareIds = session('compare_listings', []);
    @endphp

    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Ogłoszenia</h1>
                <p class="text-muted mb-0">Przeglądaj aktywne oferty dodane przez użytkowników.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('compare.index') }}" class="btn btn-outline-dark">
                    Porównaj
                    @if(!empty($compareIds))
                        <span class="badge text-bg-dark ms-1">{{ count($compareIds) }}</span>
                    @endif
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Powrót</a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Pasek boczny z filtrami -->
            <div class="col-lg-3 col-md-4">
                @include('listings._filters')
            </div>

            <!-- Lista ogłoszeń -->
            <div class="col-lg-9 col-md-8">
                <div class="row g-4">
                    @forelse ($listings as $listing)
                        <div class="col-md-6 col-xl-4">
                            @include('listings._card', ['listing' => $listing])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">Brak ogłoszeń spełniających wybrane kryteria.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
