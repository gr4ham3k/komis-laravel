<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ListingApiController;
use App\Http\Controllers\Api\ServiceApiController;

Route::apiResource('listings', ListingApiController::class)
    ->names('api.listings');

Route::apiResource('services', ServiceApiController::class)
    ->names('api.services');
