<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Strona główna
Route::get('/', function () {
    return view('welcome');
});

// ========== OGŁOSZENIA (AUTA) ==========
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware('auth');
Route::post('/listings', [ListingController::class, 'store'])->name('listings.store')->middleware('auth');
Route::get('/listings/{id}/edit', [ListingController::class, 'edit'])->name('listings.edit')->middleware('auth');
Route::put('/listings/{id}', [ListingController::class, 'update'])->name('listings.update')->middleware('auth');
Route::delete('/listings/{id}', [ListingController::class, 'destroy'])->name('listings.destroy')->middleware('auth');

// ========== SERWISY (USŁUGI MOTORYZACYJNE) ==========
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create')->middleware('auth');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store')->middleware('auth');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit')->middleware('auth');
Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update')->middleware('auth');
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('auth');
Route::post('/services/{id}/review', [ServiceController::class, 'addReview'])->name('services.review')->middleware('auth');

// ========== AUTENTYKACJA ==========
// Laravel Breeze/Fortify lub ręcznie:
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout')->middleware('auth');

// ========== PANEL UŻYTKOWNIKA ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/my-listings', [ListingController::class, 'myListings'])->name('my.listings');
    Route::get('/my-services', [ServiceController::class, 'myServices'])->name('my.services');
});

// ========== PANEL ADMINA ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/listings', [AdminController::class, 'listings'])->name('listings');
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::delete('/listings/{id}', [AdminController::class, 'deleteListing'])->name('delete.listing');
    Route::delete('/services/{id}', [AdminController::class, 'deleteService'])->name('delete.service');
});
// Tymczasowe route'y dla logowania (do czasu instalacji Breeze/Sanctum)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout')->middleware('auth');