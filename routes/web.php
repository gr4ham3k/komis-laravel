<?php

use App\Http\Controllers\Admin\Dictionary\BodyTypeController;
use App\Http\Controllers\Admin\Dictionary\BrandController;
use App\Http\Controllers\Admin\Dictionary\FuelController;
use App\Http\Controllers\Admin\Dictionary\ModelController;
use App\Http\Controllers\Admin\Dictionary\TransmissionController;
use App\Http\Controllers\Admin\DictionaryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ListingController::class, 'index'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listing/{listing}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/listings/{listing}', [ListingController::class, 'show']);

Route::middleware('auth')->group(function (): void {
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/my/listings', [ListingController::class, 'myListings'])->name('my.listings');
    Route::get('/my/listings/{listing}/edit', [ListingController::class, 'edit'])->name('my.listings.edit');
    Route::put('/my/listings/{listing}', [ListingController::class, 'update'])->name('my.listings.update');
    Route::delete('/my/listings/{listing}', [ListingController::class, 'destroy'])->name('my.listings.destroy');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function (): void {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/ban', [UserManagementController::class, 'updateBanStatus'])->name('users.ban');
        Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

Route::middleware('auth')->group(function (): void {
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::post('/services/{id}/review', [ServiceController::class, 'addReview'])->name('services.review');
    Route::get('/my/services', [ServiceController::class, 'myServices'])->name('my.services');
});

Route::prefix('admin/dictionaries')->middleware('admin')->group(function () {
    Route::get('/', [DictionaryController::class, 'index'])->name('admin.dictionaries.index');

    Route::post('/brands', [BrandController::class, 'store'])->name('admin.dictionaries.brands.store');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('admin.dictionaries.brands.destroy');
    Route::patch('/brands/{id}', [BrandController::class, 'update'])->name('admin.dictionaries.brands.update');

    Route::post('/models', [ModelController::class, 'store'])->name('admin.dictionaries.models.store');
    Route::delete('/models/{id}', [ModelController::class, 'destroy'])->name('admin.dictionaries.models.destroy');
    Route::patch('/models/{id}', [ModelController::class, 'update'])->name('admin.dictionaries.models.update');

    Route::post('/fuels', [FuelController::class, 'store'])->name('admin.dictionaries.fuels.store');
    Route::delete('/fuels/{id}', [FuelController::class, 'destroy'])->name('admin.dictionaries.fuels.destroy');
    Route::patch('/fuels/{id}', [FuelController::class, 'update'])->name('admin.dictionaries.fuels.update');

    Route::post('/transmissions', [TransmissionController::class, 'store'])->name('admin.dictionaries.transmissions.store');
    Route::delete('/transmissions/{id}', [TransmissionController::class, 'destroy'])->name('admin.dictionaries.transmissions.destroy');
    Route::patch('/transmissions/{id}', [TransmissionController::class, 'update'])->name('admin.dictionaries.transmissions.update');

    Route::post('/body-types', [BodyTypeController::class, 'store'])->name('admin.dictionaries.bodies.store');
    Route::delete('/body-types/{id}', [BodyTypeController::class, 'destroy'])->name('admin.dictionaries.bodies.destroy');
    Route::patch('/body-types/{id}', [BodyTypeController::class, 'update'])->name('admin.dictionaries.bodies.update');
});
