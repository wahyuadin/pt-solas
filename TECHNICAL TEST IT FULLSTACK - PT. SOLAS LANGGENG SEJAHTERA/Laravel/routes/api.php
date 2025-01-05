<?php

use App\Http\Controllers\api\KategoriesController;
use App\Http\Controllers\api\BukuController;
use App\Http\Controllers\api\AuthentifikasiController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthentifikasiController::class, 'register']);
        Route::post('login', [AuthentifikasiController::class, 'login']);
        Route::get('logout', [AuthentifikasiController::class, 'logout']);
    });
    Route::prefix('buku')->group(function () {
        Route::get('/', [BukuController::class, 'show']);
        Route::get('{id}', [BukuController::class, 'showbyid']);
        Route::post('/', [BukuController::class, 'post']);
        Route::put('{id}', [BukuController::class, 'put']);
        Route::delete('{id}', [BukuController::class, 'delete']);
    });
    Route::prefix('kategories')->group(function () {
        Route::get('/', [KategoriesController::class, 'show']);
        Route::get('{id}', [KategoriesController::class, 'showbyid']);
        Route::post('/', [KategoriesController::class, 'post']);
        Route::put('{id}', [KategoriesController::class, 'put']);
        Route::delete('{id}', [KategoriesController::class, 'delete']);
    });
});
