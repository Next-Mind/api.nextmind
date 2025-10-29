<?php

use App\Modules\Admin\Http\Controllers\UserCountController;
use App\Modules\AdminInvites\Http\Controllers\InviteNewAdminController;
use App\Modules\AdminInvites\Http\Controllers\AcceptAdminInvitationController;
use App\Modules\AdminInvites\Http\Controllers\DeclineAdminInvitationController;

Route::prefix('admin/count')
    ->middleware([
        'auth:sanctum',
    ])
    ->group(function () {
        Route::get('/all',           [UserCountController::class, 'all']);
        Route::get('/admins',        [UserCountController::class, 'admins']);
        Route::get('/psychologists', [UserCountController::class, 'psychologists']);
        Route::get('/students',      [UserCountController::class, 'students']);
    });


/**
 * GERENCIAMENTO DE NOVOS ADMINS
 */

Route::middleware(['auth:sanctum', 'can:admins.invite'])->group(function () {
    Route::post('/admin/invitations', InviteNewAdminController::class);
});

// Públicas (o SPA chama estas com o token do e-mail)
Route::post('/admin/invitations/accept',  AcceptAdminInvitationController::class);
Route::post('/admin/invitations/decline', DeclineAdminInvitationController::class);
