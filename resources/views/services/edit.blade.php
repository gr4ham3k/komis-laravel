{{-- resources/views/services/edit.blade.php --}}
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj usługę</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Edytuj usługę</h3>
                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-secondary btn-sm">Powrót</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('services.update', $service->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Tytuł usługi</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $service->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Opis</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="5" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Cena (PLN)</label>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $service->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Miasto</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city', $service->city) }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Images Section -->
                        @if($service->images->isNotEmpty())
                            <div class="mb-4">
                                <label class="form-label d-block">Obecne zdjęcia (Zaznacz te, które chcesz usunąć)</label>
                                <div class="row g-2">
                                    @foreach($service->images as $image)
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="card h-100 border position-relative">
                                                <img src="{{ asset('storage/services/' . $image->file_name) }}" 
                                                     class="card-img-top" 
                                                     style="height: 120px; object-fit: cover;" 
                                                     alt="Zdjęcie usługi">
                                                <div class="card-body p-2 text-center">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="del_img_{{ $image->id }}">
                                                        <label class="form-check-label text-danger" for="del_img_{{ $image->id }}">
                                                            Usuń
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">Dodaj nowe zdjęcia</label>
                            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" 
                                   multiple accept="image/*">
                            <small class="text-muted">Możesz wybrać kilka dodatkowych zdjęć jednocześnie (JPG, PNG, WEBP).</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Zapisz zmiany</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
