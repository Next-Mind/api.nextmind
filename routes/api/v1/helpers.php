<?php

use App\Http\Controllers\Admin\Counters\AdminCounterController;
use App\Http\Controllers\Admin\Counters\PsychologistCounterController;
use App\Http\Controllers\Admin\Counters\StudentCounterController;
use App\Http\Controllers\Admin\Counters\UserCounterController;
use App\Http\Controllers\Auth\PsychologistDocumentController;

/**
 * Gerador URL temporárias para documentos de psicólogos
 */
Route::get('/psychologists/documents/{document}/file', [PsychologistDocumentController::class, 'show'])
    ->middleware(['auth:sanctum','signed']) // auth + assinatura de URL
    ->name('psychologists.documents.file');

/**
 * CONTADOR DE USUÁRIOS
 */

Route::middleware(['role:admin','auth:sanctum'])->prefix('/admin/count')->group(function () {
    Route::get('admins', AdminCounterController::class);
    Route::get('students', StudentCounterController::class);
    Route::get('psychologists', PsychologistCounterController::class);
    Route::get('all', UserCounterController::class);
});
