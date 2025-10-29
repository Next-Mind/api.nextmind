<?php

use App\Modules\Psychologists\Http\Controllers\PsychologistApprovalController;

Route::middleware(['auth:sanctum'])->prefix('admin/psychologists')->name('admin.psychologists.')->group(function () {
    Route::get('/',        [PsychologistApprovalController::class, 'index'])->name('index');
    Route::patch('/documents/{document}/approve', [PsychologistApprovalController::class, 'approveDocument']);
    Route::patch('/documents/{document}/repprove', [PsychologistApprovalController::class, 'disapproveDocument']);
});
