<?php

use App\Http\Controllers\Admin\Dictionary\BodyTypeController;
use App\Http\Controllers\Admin\Dictionary\BrandController;
use App\Http\Controllers\Admin\Dictionary\FuelController;
use App\Http\Controllers\Admin\Dictionary\ModelController;
use App\Http\Controllers\Admin\Dictionary\TransmissionController;
use App\Http\Controllers\Admin\DictionaryController;
use App\Http\Controllers\Admin\ServiceAdminController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
Route::post('/listings/create',[ListingController::class,'store'])->name('listings.store');


Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
Route::post('/services/{id}/review', [ServiceController::class, 'addReview'])->name('services.review');

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

Route::prefix('admin/services')->name('admin.services.')->group(function () {
    Route::get('/', [ServiceAdminController::class, 'index'])->name('index');
    Route::post('/', [ServiceAdminController::class, 'store'])->name('store');
    Route::patch('/{id}', [ServiceAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [ServiceAdminController::class, 'destroy'])->name('destroy');
});
