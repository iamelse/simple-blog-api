<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\CategoryController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('categories', CategoryController::class)->only(['index', 'store']);
    Route::apiResource('posts', PostController::class);

    Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'toggle']);
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
});
