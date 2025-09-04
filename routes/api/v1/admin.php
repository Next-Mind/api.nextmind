<?php

use App\Http\Controllers\Admin\PsychologistApprovalController;

/**
 * GERENCIAMENTO DE NOVOS PSICÓLOGOS
 */
Route::middleware(['auth:sanctum'])->prefix('admin/psychologists')->name('admin.psychologists.')->group(function () {
    Route::get('/',        [PsychologistApprovalController::class, 'index'])->name('index');
    // Route::get('{id}',     [PsychologistApprovalController::class, 'show'])->name('show');
    // Route::patch('{id}',   [PsychologistApprovalController::class, 'update'])->name('update');
    // Route::patch('{id}/status', [PsychologistApprovalController::class, 'updateStatus'])->name('status');
});