@php
    $selectedUserId = old('user_id', $service->user_id ?? '');
    $selectedStatus = old('status', $service->status ?? 'active');
    $prefix = $prefix ?? 'default';
    $selectedUser = $selectedUserId ? $users->firstWhere('id', $selectedUserId) : null;
    $selectedUserLabel = $selectedUser ? $selectedUser->name . ' (' . $selectedUser->email . ')' : '';
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Tytuł usługi</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $service->title ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" @selected($selectedStatus === 'active')>Aktywna</option>
            <option value="inactive" @selected($selectedStatus === 'inactive')>Nieaktywna</option>
            <option value="deleted" @selected($selectedStatus === 'deleted')>Usunięta</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Cena (PLN)</label>
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $service->price ?? '') }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Wybierz lokalizację</label>

        <div class="input-group mb-2">
            <input type="text" id="map-search-{{ $prefix }}" class="form-control"
                placeholder="Wpisz miasto (np. Warszawa)">
            <button type="button" class="btn btn-outline-secondary" id="map-search-btn-{{ $prefix }}">
                Szukaj
            </button>
        </div>

        <div id="map-status-{{ $prefix }}" class="text-muted small mt-2"></div>

        <div id="map-{{ $prefix }}" style="height: 300px; border-radius: 10px;"></div>
        <input type="hidden" name="city" id="city-{{ $prefix }}" value="{{ old('city', $service->city ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Właściciel</label>
        <input type="text" id="user-search-{{ $prefix }}" class="form-control"
            list="global-users-list"
            placeholder="Wpisz aby wyszukać..." autocomplete="off"
            value="{{ $selectedUserLabel }}">
        <input type="hidden" name="user_id" id="user-id-{{ $prefix }}" value="{{ $selectedUserId }}">
    </div>

    <div class="col-12">
        <label class="form-label">Opis</label>
        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $service->description ?? '') }}</textarea>
    </div>
</div>
