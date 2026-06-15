@extends('layouts.app')

@push('styles')
    <style>
        body {
            background: #f8f9fa;
        }

        .tab-content {
            background: #fff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .dictionary-item {
            gap: 12px;
        }

        .dictionary-actions {
            flex-shrink: 0;
        }

        .dictionary-edit {
            border-top: 1px solid #dee2e6;
            margin-top: 12px;
            padding-top: 12px;
        }
    </style>
@endpush

@section('content')

    @php
        $activeTab = old('active_tab', session('activeTab', 'brands'));
    @endphp

    <div class="container py-4">


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
            <h2 class="mb-0">Panel admina - Słowniki</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-users"></i> Użytkownicy
                </a>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-wrench"></i> Usługi
                </a>
            </div>
        </div>

        <ul class="nav nav-tabs" id="dictTabs" role="tablist">

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'brands' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#brands">
                    Marki
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'models' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#models">
                    Modele
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'fuels' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#fuels">
                    Paliwa
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'transmissions' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#transmissions">
                    Skrzynie biegów
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'bodytypes' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#bodytypes">
                    Nadwozia
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'tags' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#tags">
                    Tagi
                </button>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane fade {{ $activeTab == 'brands' ? 'show active' : '' }}" id="brands">
                <h4>Marki</h4>

                <form method="POST" action="{{ route('admin.dictionaries.brands.store') }}" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Nowa marka">
                    <input type="hidden" name="active_tab" value="brands">
                    <button class="btn btn-primary">Dodaj</button>
                </form>

                <div class="list-group">
                    @foreach ($brands as $brand)
                        <div class="list-group-item">
                            <div class="dictionary-item d-flex justify-content-between align-items-center">
                                <span>{{ $brand->name }}</span>

                                <div class="dictionary-actions d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#edit-brand-{{ $brand->id }}">
                                        Edytuj
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.dictionaries.brands.destroy', $brand->id) }}"
                                        onsubmit="return confirm('Na pewno usunąć tę markę?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse dictionary-edit" id="edit-brand-{{ $brand->id }}">
                                <form method="POST" action="{{ route('admin.dictionaries.brands.update', $brand->id) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" class="form-control" value="{{ $brand->name }}"
                                        placeholder="Nazwa marki">

                                    <input type="hidden" name="active_tab" value="brands">

                                    <button class="btn btn-success">Zapisz</button>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#edit-brand-{{ $brand->id }}">Anuluj</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade {{ $activeTab == 'models' ? 'show active' : '' }}" id="models">
                <h4>Modele</h4>

                <form method="POST" action="{{ route('admin.dictionaries.models.store') }}" class="row g-2 mb-3">
                    @csrf

                    <div class="col-md-5">
                        <select name="brand_id" class="form-select">
                            <option value="">Wybierz markę</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <input type="text" name="name" class="form-control" placeholder="Model">
                    </div>

                    <input type="hidden" name="active_tab" value="models">

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Dodaj</button>
                    </div>
                </form>

                <div class="list-group">
                    @foreach ($models as $model)
                        <div class="list-group-item">
                            <div class="dictionary-item d-flex justify-content-between align-items-center">
                                <span><strong>{{ $model->brand->name }}</strong> - {{ $model->name }}</span>

                                <div class="dictionary-actions d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#edit-model-{{ $model->id }}">
                                        Edytuj
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.dictionaries.models.destroy', $model->id) }}"
                                        onsubmit="return confirm('Na pewno usunąć ten model?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse dictionary-edit" id="edit-model-{{ $model->id }}">
                                <form method="POST" action="{{ route('admin.dictionaries.models.update', $model->id) }}"
                                    class="row g-2">
                                    @csrf
                                    @method('PATCH')

                                    <div class="col-md-4">
                                        <select name="brand_id" class="form-select">
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" @selected($model->brand_id === $brand->id)>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $model->name }}" placeholder="Nazwa modelu">
                                    </div>

                                    <input type="hidden" name="active_tab" value="models">

                                    <div class="col-md-2">
                                        <button class="btn btn-success w-100">Zapisz</button>
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-outline-secondary w-100" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#edit-model-{{ $model->id }}">Anuluj</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade {{ $activeTab == 'fuels' ? 'show active' : '' }}" id="fuels">
                <h4>Paliwa</h4>

                <form method="POST" action="{{ route('admin.dictionaries.fuels.store') }}" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Nowe paliwo">
                    <input type="hidden" name="active_tab" value="fuels">

                    <button class="btn btn-primary">Dodaj</button>
                </form>

                <div class="list-group">
                    @foreach ($fuels as $fuel)
                        <div class="list-group-item">
                            <div class="dictionary-item d-flex justify-content-between align-items-center">
                                <span>{{ $fuel->name }}</span>

                                <div class="dictionary-actions d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#edit-fuel-{{ $fuel->id }}">
                                        Edytuj
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.dictionaries.fuels.destroy', $fuel->id) }}"
                                        onsubmit="return confirm('Na pewno usunąć to paliwo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse dictionary-edit" id="edit-fuel-{{ $fuel->id }}">
                                <form method="POST" action="{{ route('admin.dictionaries.fuels.update', $fuel->id) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $fuel->name }}" placeholder="Nazwa paliwa">
                                    <input type="hidden" name="active_tab" value="fuels">
                                    <button class="btn btn-success">Zapisz</button>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#edit-fuel-{{ $fuel->id }}">Anuluj</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade {{ $activeTab == 'transmissions' ? 'show active' : '' }}" id="transmissions">
                <h4>Skrzynie biegów</h4>

                <form method="POST" action="{{ route('admin.dictionaries.transmissions.store') }}"
                    class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Nowa skrzynia">
                    <input type="hidden" name="active_tab" value="transmissions">
                    <button class="btn btn-primary">Dodaj</button>
                </form>

                <div class="list-group">
                    @foreach ($transmissions as $t)
                        <div class="list-group-item">
                            <div class="dictionary-item d-flex justify-content-between align-items-center">
                                <span>{{ $t->name }}</span>

                                <div class="dictionary-actions d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#edit-transmission-{{ $t->id }}">
                                        Edytuj
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.dictionaries.transmissions.destroy', $t->id) }}"
                                        onsubmit="return confirm('Na pewno usunąć tę skrzynię?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse dictionary-edit" id="edit-transmission-{{ $t->id }}">
                                <form method="POST"
                                    action="{{ route('admin.dictionaries.transmissions.update', $t->id) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $t->name }}" placeholder="Nazwa skrzyni">
                                    <input type="hidden" name="active_tab" value="transmissions">
                                    <button class="btn btn-success">Zapisz</button>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#edit-transmission-{{ $t->id }}">Anuluj</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade {{ $activeTab == 'bodytypes' ? 'show active' : '' }}" id="bodytypes">
                <h4>Nadwozia</h4>

                <form method="POST" action="{{ route('admin.dictionaries.bodies.store') }}" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Typ nadwozia">
                    <input type="hidden" name="active_tab" value="bodytypes">
                    <button class="btn btn-primary">Dodaj</button>
                </form>

                <div class="list-group">
                    @foreach ($bodyTypes as $bt)
                        <div class="list-group-item">
                            <div class="dictionary-item d-flex justify-content-between align-items-center">
                                <span>{{ $bt->name }}</span>

                                <div class="dictionary-actions d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#edit-body-type-{{ $bt->id }}">
                                        Edytuj
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.dictionaries.bodies.destroy', $bt->id) }}"
                                        onsubmit="return confirm('Na pewno usunąć ten typ nadwozia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse dictionary-edit" id="edit-body-type-{{ $bt->id }}">
                                <form method="POST" action="{{ route('admin.dictionaries.bodies.update', $bt->id) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $bt->name }}" placeholder="Typ nadwozia">
                                    <input type="hidden" name="active_tab" value="bodytypes">
                                    <button class="btn btn-success">Zapisz</button>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#edit-body-type-{{ $bt->id }}">Anuluj</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade {{ $activeTab == 'tags' ? 'show active' : '' }}" id="tags">
                <h4>Tagi</h4>

                <form method="POST" action="{{ route('admin.dictionaries.tags.store') }}" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Nowy tag">
                    <input type="hidden" name="active_tab" value="tags">
                    <button class="btn btn-primary">Dodaj</button>
                </form>

                <div class="list-group">
                    @foreach ($tags as $tag)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $tag->name }}</span>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse"
                                        data-bs-target="#edit-tag-{{ $tag->id }}">
                                        Edytuj
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.dictionaries.tags.destroy', $tag->id) }}"
                                        onsubmit="return confirm('Na pewno usunąć ten tag?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Usuń</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse mt-2" id="edit-tag-{{ $tag->id }}">
                                <form method="POST" action="{{ route('admin.dictionaries.tags.update', $tag->id) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <input type="text" name="name" class="form-control"
                                        value="{{ $tag->name }}">
                                    <input type="hidden" name="active_tab" value="tags">
                                    <button class="btn btn-success">Zapisz</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection
