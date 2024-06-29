<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CanvaController;
use Illuminate\Support\Facades\Route;

/**
 * Canvas
 */

 // authenticated
 Route::middleware('auth')->group(function () {
     Route::post('/canvas/color/add', [CanvaController::class, 'addColors']);
     Route::post('/canvas/create', [CanvaController::class, 'createCanva']);
     Route::get('/session', [AuthController::class, 'getSession']);
});
Route::get('/canvas', [CanvaController::class, 'getCanvas']);

 // public
Route::get('/canvas/{id}', [CanvaController::class, 'getCanva']);
