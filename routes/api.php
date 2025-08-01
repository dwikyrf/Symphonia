<?php

use App\Http\Controllers\Api\LocationController; // Pastikan ini ada!
use Illuminate\Support\Facades\Route;

Route::prefix('locations')->group(function () {
    Route::get('provinces', [LocationController::class, 'getProvinces']);
    Route::get('cities', [LocationController::class, 'getCities']);
    Route::get('districts', [LocationController::class, 'getDistricts']);
    Route::get('postal-codes', [LocationController::class, 'getPostalCodes']);
});