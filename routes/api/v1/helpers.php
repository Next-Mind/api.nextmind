<?php

use App\Http\Controllers\Auth\PsychologistDocumentController;

/**
 * Gerador URL temporárias para documentos de psicólogos
 */
Route::get('/psychologists/documents/{document}/file', [PsychologistDocumentController::class, 'show'])
    ->middleware(['auth:sanctum','signed']) // auth + assinatura de URL
    ->name('psychologists.documents.file');