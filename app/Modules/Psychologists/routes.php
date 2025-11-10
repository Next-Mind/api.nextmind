<?php

use App\Modules\Psychologists\Http\Controllers\PsychologistApprovalController;
use App\Modules\Psychologists\Http\Controllers\PsychologistDocumentController;

Route::middleware(['auth:sanctum'])->prefix('admin/psychologists')->name('admin.psychologists.')->group(function () {
    Route::get('/',        [PsychologistApprovalController::class, 'index'])->name('index');
    Route::patch('/documents/{document}/approve', [PsychologistApprovalController::class, 'approveDocument']);
    Route::patch('/documents/{document}/repprove', [PsychologistApprovalController::class, 'disapproveDocument']);
    Route::patch('/documents/repprove', [PsychologistApprovalController::class, 'disapproveDocuments']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get(
        'psychologists/documents/{document}/file',
        [PsychologistDocumentController::class, 'show']
    )->name('psychologists.documents.file')
        ->middleware('signed');
});
