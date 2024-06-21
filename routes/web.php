<?php

use App\Http\Controllers\CanvaController;
use Illuminate\Support\Facades\Route;

/**
 * Canvas
 */

 // authenticated
 Route::middleware('auth')->group(function () {
     Route::post('/canvas/color/add', [CanvaController::class, 'addColors']);
     Route::post('/canvas/create', [CanvaController::class, 'createCanva']);
 });

 // public
Route::get('/canvas/{id}', [CanvaController::class, 'getCanva']);
