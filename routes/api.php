<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CanvaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function (Request $request) {
    return "yep";
});

// Auth
Route::post('tokens/create', [AuthController::class, 'create']);


// Canvas
Route::get('/canva/{id}', [CanvaController::class, 'getCanva']);
Route::post('/place-pixel', [CanvaController::class, 'placePixel'])
    ->middleware(['auth:sanctum', 'abilities:canvas:place-pixels']);;
Route::post('/canvas/create', [CanvaController::class, 'createCanva']);
Route::post('/canvas/color/add', [CanvaController::class, 'addColors']);
