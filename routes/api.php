<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta pública para registrar nuevos usuarios
Route::post('/register', [UserController::class, 'store']);

// Ruta pública para solicitar reseteo de contraseña
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Ruta pública para cambiar la contraseña con el token
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Rutas protegidas para la gestión de usuarios
    Route::apiResource('users', UserController::class)->except(['store']);
    Route::apiResource('favorites', FavoriteController::class)->only([
        'index',
        'store',
        'destroy'
    ]);
});
