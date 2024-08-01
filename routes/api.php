<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\VisitorController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['role:artist'])->group(function () {
        Route::get('/artworks', [ArtworkController::class, 'index']);
        Route::post('/artworks/store', [ArtworkController::class, 'store']);
        Route::put('/artworks/update/{artwork}', [ArtworkController::class, 'update']);
        Route::delete('/artworks/delete/{artwork}', [ArtworkController::class, 'destroy']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin-only', [AdminController::class, 'index']);
    });

    Route::middleware(['role:visitor'])->group(function () {
        Route::get('/artworks/show', [VisitorController::class, 'index']);
        Route::get('/artworks/{id}', [VisitorController::class, 'show']);
        Route::post('/artworks/{id}/comment', [VisitorController::class, 'comment']);
    });
});
