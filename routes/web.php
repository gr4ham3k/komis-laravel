<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/listing/{id}',[ListingController::class,'show']);
Route::get('/listings/create',[ListingController::class,'create']);
