<?php

use App\Http\Controllers\Admin\InviteNewAdminController;
use App\Http\Controllers\Admin\PsychologistApprovalController;
use App\Http\Controllers\Admin\AcceptAdminInvitationController;
use App\Http\Controllers\Admin\DeclineAdminInvitationController;

/**
 * GERENCIAMENTO DE NOVOS PSICÓLOGOS
 */
Route::middleware(['auth:sanctum'])->prefix('admin/psychologists')->name('admin.psychologists.')->group(function () {
    Route::get('/',        [PsychologistApprovalController::class, 'index'])->name('index');
    Route::patch('/documents/{document}/approve',[PsychologistApprovalController::class,'approveDocument']);
    Route::patch('/documents/{document}/repprove',[PsychologistApprovalController::class,'disapproveDocument']);
});

/**
 * GERENCIAMENTO DE NOVOS ADMINS
 */
Route::middleware(['auth:sanctum','can:admins.invite'])->group(function () {
    Route::post('/admin/invitations', InviteNewAdminController::class);
});

// Públicas (o SPA chama estas com o token do e-mail)
Route::post('/admin/invitations/accept',  AcceptAdminInvitationController::class);
Route::post('/admin/invitations/decline', DeclineAdminInvitationController::class);


