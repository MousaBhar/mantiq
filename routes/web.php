<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LessonProgressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\CourseEnrollmentController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\LessonController;


// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])
    ->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.submit');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// Google Authentication Routes
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');


// Public Learning Routes
Route::get('/tracks/{track}', [LearningController::class, 'index'])
    ->whereIn('track', ['tech', 'business'])
    ->name('learning.index');

Route::get('/contents/{content}', [LearningController::class, 'show'])
    ->whereNumber('content')
    ->name('learning.show');


// Authenticated User Routes
Route::middleware('auth')->group(function () {

    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Podcast Library
    Route::get('/podcasts', [LearningController::class, 'podcasts'])
        ->name('learning.podcasts');

    // Lesson Details
    Route::get('/lessons/{lesson}', [LearningController::class, 'lesson'])
        ->whereNumber('lesson')
        ->name('learning.lessons.show');

    // Course Enrollment
    Route::post('/contents/{content}/enroll', [CourseEnrollmentController::class, 'enroll'])
        ->whereNumber('content')
        ->name('learning.enroll');

    // Lesson Progress
    Route::post('/lessons/{lesson}/complete', [LessonProgressController::class, 'complete'])
        ->whereNumber('lesson')
        ->name('learning.lessons.complete');
});


// Admin Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::resource('contents', ContentController::class);

        Route::resource('contents.lessons', LessonController::class);
    });
