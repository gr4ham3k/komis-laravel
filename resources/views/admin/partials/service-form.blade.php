@php
    $selectedUserId = old('user_id', $service->user_id ?? '');
    $selectedStatus = old('status', $service->status ?? 'active');
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

    <div class="col-md-4">
        <label class="form-label">Miasto</label>
        <input type="text" name="city" class="form-control" value="{{ old('city', $service->city ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Właściciel</label>
        <select name="user_id" class="form-select" required>
            <option value="">Wybierz użytkownika</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected((string) $selectedUserId === (string) $user->id)>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Opis</label>
        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $service->description ?? '') }}</textarea>
    </div>
</div>
