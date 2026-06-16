<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ListingApiController;

Route::apiResource('listings', ListingApiController::class)
    ->names('api.listings');
