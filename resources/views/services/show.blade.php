@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="h3">{{ $service->title }}</h1>
                    <p class="text-muted mb-3">
                        {{ $service->city }} | Dodano: {{ $service->created_at->format('d.m.Y') }} | Wyswietlenia: {{ $service->views_count }}
                    </p>

                    <p class="mb-0">{{ $service->description }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Opinie</h2>

                    @auth
                        <form method="POST" action="{{ route('services.review', $service->id) }}" class="mb-4">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="rating" class="form-label">Ocena</label>
                                    <select id="rating" name="rating" class="form-select" required>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }}/5</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="comment" class="form-label">Komentarz</label>
                                    <textarea id="comment" name="comment" rows="3" class="form-control" placeholder="Twoja opinia"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Dodaj opinie</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Aby dodac opinie, zaloguj sie.
                        </div>
                    @endauth

                    @forelse($service->reviews as $review)
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $review->user->name }}</strong>
                                <span class="text-muted small">{{ $review->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="small mb-2">Ocena: {{ $review->rating }}/5</div>
                            <div>{{ $review->comment }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Brak opinii.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h4 text-success">{{ number_format((float) $service->price, 2, ',', ' ') }} PLN</h2>
                    <p class="mb-3">Wystawiajacy: {{ $service->user->name }}</p>
                    <a class="btn btn-outline-primary w-100 mb-2" href="mailto:{{ $service->user->email }}">Kontakt email</a>

                    @auth
                        @if(auth()->id() === $service->user_id || auth()->user()->is_admin)
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning w-100 mb-2">Edytuj</a>
                            <form method="POST" action="{{ route('services.destroy', $service->id) }}" onsubmit="return confirm('Na pewno usunac te usluge?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Usun</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
