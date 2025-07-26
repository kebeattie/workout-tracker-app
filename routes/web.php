<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ProgressController;



Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
    Route::delete('/workouts/{id}', [WorkoutController::class, 'destroy'])->name('workouts.destroy'); 
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
    Route::get('/api/progress-data', [ProgressController::class, 'getProgressData'])->middleware('auth');
     
});

require __DIR__.'/auth.php';
