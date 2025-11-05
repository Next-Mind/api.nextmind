<?php

use App\Modules\Audits\Http\Controllers\AuditController;
use Illuminate\Support\Facades\Route;

Route::prefix('audits')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('audits.index');
        Route::get('/{type}/{id}', [AuditController::class, 'history'])->name('audits.history');
    });
