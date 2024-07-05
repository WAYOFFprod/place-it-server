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
     Route::post('/canvas/create', [CanvaController::class, 'createCanva'])
        ->middleware(['permission:create-canvas']);
     Route::delete('/canvas/{id}', [CanvaController::class, 'deleteCanva']);
     Route::get('/canva/join/{id}', [CanvaController::class, 'joinCanva']);
     Route::get('/session', [AuthController::class, 'getSession']);
     Route::post('/user/update', [AuthController::class, 'update']);
});
Route::get('/canvas', [CanvaController::class, 'getCanvas']);

 // public
Route::get('/canvas/{id}', [CanvaController::class, 'getCanva']);
