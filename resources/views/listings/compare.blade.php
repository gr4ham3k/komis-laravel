@extends('layouts.app')

@section('content')
    @php
        $bestMax = [
            'year' => $listings->max('year'),
            'engine_capacity' => $listings->max('engine_capacity'),
            'power_hp' => $listings->max('power_hp'),
        ];

        $bestMin = [
            'price' => $listings->min('price'),
            'mileage' => $listings->min('mileage'),
        ];
    @endphp

    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Porownanie ogloszen</h1>
                <p class="text-muted mb-0">
                    Zielone pola oznaczaja najlepsza wartosc w danym wierszu. Nizsza cena i mniejszy przebieg sa lepsze,
                    a dla pozostalych parametrów wyzsza wartosc dostaje wyróżnienie.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('listings.index') }}" class="btn btn-outline-secondary">Wroc do ogloszen</a>
                @if($listings->isNotEmpty())
                    <form method="POST" action="{{ route('compare.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Wyczysc porownanie</button>
                    </form>
                @endif
            </div>
        </div>

        @if($listings->isEmpty())
            <div class="alert alert-info">
                Lista porownania jest pusta. Wroc do ogloszen i dodaj auta do porownania.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle table-bordered bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="min-width: 220px;">Parametr</th>
                            @foreach($listings as $listing)
                                <th scope="col" style="min-width: 260px;">
                                    <div class="d-flex flex-column gap-2">
                                        <div>
                                            <div class="fw-semibold">{{ $listing->title }}</div>
                                            <small class="text-white-50">{{ $listing->brand?->name }} {{ $listing->carModel?->name }}</small>
                                        </div>
                                        <img
                                            src="{{ $listing->images->first() ? asset('storage/listings/' . $listing->images->first()->file_name) : 'https://placehold.co/260x160?text=No+Image' }}"
                                            alt="{{ $listing->title }}"
                                            class="img-fluid rounded"
                                            style="height: 160px; object-fit: cover;"
                                        >
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('listings.show', $listing) }}" class="btn btn-sm btn-light">Szczegoly</a>
                                            <form method="POST" action="{{ route('compare.destroy', $listing) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-light">Usun</button>
                                            </form>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = [
                                'Cena' => ['field' => 'price', 'suffix' => ' PLN', 'type' => 'money', 'best' => 'min'],
                                'Rok' => ['field' => 'year', 'suffix' => '', 'type' => 'number', 'best' => 'max'],
                                'Przebieg' => ['field' => 'mileage', 'suffix' => ' km', 'type' => 'number', 'best' => 'min'],
                                'Pojemnosc silnika' => ['field' => 'engine_capacity', 'suffix' => ' cm3', 'type' => 'number', 'best' => 'max'],
                                'Moc' => ['field' => 'power_hp', 'suffix' => ' KM', 'type' => 'number', 'best' => 'max'],
                                'Miasto' => ['field' => 'city', 'suffix' => '', 'type' => 'text', 'best' => null],
                                'Kolor' => ['field' => 'color', 'suffix' => '', 'type' => 'text', 'best' => null],
                                'Paliwo' => ['field' => 'fuel', 'suffix' => '', 'type' => 'relation', 'relation' => 'fuel', 'best' => null],
                                'Skrzynia' => ['field' => 'transmission', 'suffix' => '', 'type' => 'relation', 'relation' => 'transmission', 'best' => null],
                                'Nadwozie' => ['field' => 'bodyType', 'suffix' => '', 'type' => 'relation', 'relation' => 'bodyType', 'best' => null],
                                'Sprzedajacy' => ['field' => 'user', 'suffix' => '', 'type' => 'relation', 'relation' => 'user', 'best' => null],
                            ];
                        @endphp

                        @foreach($rows as $label => $config)
                            <tr>
                                <th scope="row" class="table-light">{{ $label }}</th>
                                @foreach($listings as $listing)
                                    @php
                                        $value = match ($config['type']) {
                                            'money' => number_format($listing->{$config['field']}, 2) . $config['suffix'],
                                            'relation' => $listing->{$config['relation']}?->name ?? '-',
                                            default => $listing->{$config['field']} . $config['suffix'],
                                        };

                                        $isBest = match ($config['best']) {
                                            'max' => $listing->{$config['field']} == $bestMax[$config['field']],
                                            'min' => $listing->{$config['field']} == $bestMin[$config['field']],
                                            default => false,
                                        };
                                    @endphp

                                    <td class="{{ $isBest ? 'table-success fw-semibold' : '' }}">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr>
                            <th scope="row" class="table-light">Status</th>
                            @foreach($listings as $listing)
                                <td class="{{ $listing->status === 'active' ? 'table-success fw-semibold' : '' }}">
                                    {{ $listing->status === 'active' ? 'Aktywne' : 'Nieaktywne' }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
