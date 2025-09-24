<?php

use App\Http\Controllers\Appointments\AvailabilityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/psychologists/{psychologist}/availabilities', [AvailabilityController::class, 'index'])
        ->name('availabilities.index');

    Route::post('/availabilities', [AvailabilityController::class, 'store'])
        ->name('availabilities.store'); 

    Route::patch('/availabilities/{availability}/status', [AvailabilityController::class, 'updateStatus'])
        ->name('availabilities.updateStatus');

    Route::post('/availabilities/{availability}/schedule', [AvailabilityController::class, 'schedule'])
        ->middleware('can:appointments.book')
        ->name('availabilities.schedule');
});