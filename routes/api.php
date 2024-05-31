<?php

use App\Http\Controllers\CanvaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function (Request $request) {
    return "yep";
});

Route::get('/canva/{id}', [CanvaController::class, 'getCanva']);
Route::post('/place-pixel', [CanvaController::class, 'placePixel']);
Route::post('/canvas/create', [CanvaController::class, 'createCanva']);
Route::post('/canvas/color/add', [CanvaController::class, 'addColors']);
