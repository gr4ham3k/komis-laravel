<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/listings', [ListingController::class, 'apiIndex'])->name('api.listings.index');
