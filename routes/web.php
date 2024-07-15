<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CanvaController;
use App\Http\Controllers\FriendController;
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
     Route::post('/canva/like', [CanvaController::class, 'toggleLike']);
     Route::get('/session', [AuthController::class, 'getSession']);
     Route::post('/user/update', [AuthController::class, 'update']);

     // friends
     Route::post('/friend/request', [FriendController::class, 'requestFriend']);
     Route::post('/friend/accept', [FriendController::class, 'acceptFriend']);
     Route::post('/friend/reject', [FriendController::class, 'rejectFriend']);
     Route::post('/friend/block', [FriendController::class, 'blockFriend']);

     Route::get('/friends/requests', [FriendController::class, 'getRequests']);
     Route::get('/friends/blocked', [FriendController::class, 'getBlockedFriends']);
     Route::get('/friends', [FriendController::class, 'getFriends']);
});
Route::get('/canvas', [CanvaController::class, 'getCanvas']);

 // public
Route::get('/canvas/{id}', [CanvaController::class, 'getCanva']);
