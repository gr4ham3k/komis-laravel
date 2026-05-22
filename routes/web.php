<?php

use App\Http\Controllers\Admin\Dictionary\BodyTypeController;
use App\Http\Controllers\Admin\Dictionary\BrandController;
use App\Http\Controllers\Admin\Dictionary\FuelController;
use App\Http\Controllers\Admin\Dictionary\ModelController;
use App\Http\Controllers\Admin\Dictionary\TransmissionController;
use App\Http\Controllers\Admin\DictionaryController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware('auth');
Route::post('/listings/create',[ListingController::class,'store'])->name('listings.store');
Route::get('/listings/{id}/edit', [ListingController::class, 'edit'])->name('listings.edit')->middleware('auth');
Route::put('/listings/{id}', [ListingController::class, 'update'])->name('listings.update')->middleware('auth');
Route::delete('/listings/{id}', [ListingController::class, 'destroy'])->name('listings.destroy')->middleware('auth');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create')->middleware('auth');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store')->middleware('auth');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit')->middleware('auth');
Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update')->middleware('auth');
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('auth');
Route::post('/services/{id}/review', [ServiceController::class, 'addReview'])->name('services.review')->middleware('auth');


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

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/my-listings', [ListingController::class, 'myListings'])->name('my.listings');
    Route::get('/my-services', [ServiceController::class, 'myServices'])->name('my.services');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/listings', [AdminController::class, 'listings'])->name('listings');
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::delete('/listings/{id}', [AdminController::class, 'deleteListing'])->name('delete.listing');
    Route::delete('/services/{id}', [AdminController::class, 'deleteService'])->name('delete.service');
});

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

Route::prefix('admin/dictionaries')->group(function () {
    Route::get('/',[DictionaryController::class,'index'])->name('admin.dictionaries.index');

    Route::post('/brands',[BrandController::class,'store'])->name('admin.dictionaries.brands.store');
    Route::delete('/brands/{id}',[BrandController::class,'destroy'])->name('admin.dictionaries.brands.destroy');
    Route::patch('/brands/{id}',[BrandController::class,'update'])->name('admin.dictionaries.brands.update');

    Route::post('/models',[ModelController::class,'store'])->name('admin.dictionaries.models.store');
    Route::delete('/models/{id}',[ModelController::class,'destroy'])->name('admin.dictionaries.models.destroy');
    Route::patch('/models/{id}',[ModelController::class,'update'])->name('admin.dictionaries.models.update');

    Route::post('/fuels',[FuelController::class,'store'])->name('admin.dictionaries.fuels.store');
    Route::delete('/fuels/{id}',[FuelController::class,'destroy'])->name('admin.dictionaries.fuels.destroy');
    Route::patch('/fuels/{id}',[FuelController::class,'update'])->name('admin.dictionaries.fuels.update');

    Route::post('/transmissions',[TransmissionController::class,'store'])->name('admin.dictionaries.transmissions.store');
    Route::delete('/transmissions/{id}',[TransmissionController::class,'destroy'])->name('admin.dictionaries.transmissions.destroy');
    Route::patch('/transmissions/{id}',[TransmissionController::class,'update'])->name('admin.dictionaries.transmissions.update');

    Route::post('/body-types',[BodyTypeController::class,'store'])->name('admin.dictionaries.bodies.store');
    Route::delete('/body-types/{id}',[BodyTypeController::class,'destroy'])->name('admin.dictionaries.bodies.destroy');
    Route::patch('/body-types/{id}',[BodyTypeController::class,'update'])->name('admin.dictionaries.bodies.update');

});
