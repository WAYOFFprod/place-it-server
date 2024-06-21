<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CanvaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Canvas
 */
//liveserver
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/place-pixel', [CanvaController::class, 'placePixel'])
        ->middleware(['abilities:canvas:place-pixels']);
});
