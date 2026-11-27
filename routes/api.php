<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\Api\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Public Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::post('/forgot-password', [PasswordResetController::class, 'requestOtp'])->middleware('throttle:5,1');
Route::post('/verify-reset-otp', [PasswordResetController::class, 'verifyOtp'])->middleware('throttle:10,1');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Public Categories Routes
|--------------------------------------------------------------------------
*/

Route::get('/categories', [CategoryController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Public Catalog Routes
|--------------------------------------------------------------------------
*/

Route::get('/contents', [CatalogController::class, 'index']);
Route::get('/contents/{content}', [CatalogController::class, 'show'])->whereNumber('content');
Route::get('/search', [CatalogController::class, 'search'])->middleware('throttle:60,1');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/me/progress', [CatalogController::class, 'progress']);

    // Mentor (assistant)
    Route::get('/assistant/conversations', [AssistantController::class, 'conversations']);
    Route::get('/assistant/conversations/{conversation}', [AssistantController::class, 'show'])->whereNumber('conversation');
    Route::delete('/assistant/conversations/{conversation}', [AssistantController::class, 'destroy'])->whereNumber('conversation');
    Route::post('/assistant/messages', [AssistantController::class, 'send'])->middleware('throttle:assistant');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

});
