<?php

use Illuminate\Support\Facades\Route;
use App\Models\Appointments\Appointment;
use App\Http\Controllers\Appointments\AppointmentController;
use App\Http\Controllers\Users\PsychologistController;
use App\Http\Controllers\Appointments\AvailabilityController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/psychologists',[PsychologistController::class,'index']);
    Route::get('/psychologists/{uuid}',[PsychologistController::class,'show']);

    Route::get('/psychologists/{psychologist}/availabilities', [AvailabilityController::class, 'index'])
        ->name('availabilities.index');

    Route::post('/availabilities', [AvailabilityController::class, 'store'])
        ->name('availabilities.store'); 

    Route::patch('/availabilities/{availability}/status', [AvailabilityController::class, 'updateStatus'])
        ->name('availabilities.updateStatus');

    Route::post('/availabilities/{availability}/schedule', [AvailabilityController::class, 'schedule'])
        ->middleware('can:appointments.book')
        ->name('availabilities.schedule');


    // CRUD padrão → mapeia para viewAny, view, create, update, delete da AppointmentPolicy
    Route::apiResource('appointments', AppointmentController::class);

    // Ações extras
    Route::post('appointments/book', [AppointmentController::class, 'book'])
        ->name('appointments.book')
        ->can('book', Appointment::class);

    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel')
        ->can('cancel', 'appointment');

    Route::post('appointments/{appointment}/perform', [AppointmentController::class, 'perform'])
        ->name('appointments.perform')
        ->can('perform', 'appointment');
});