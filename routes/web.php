<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutController;



Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');      
});

require __DIR__.'/auth.php';
