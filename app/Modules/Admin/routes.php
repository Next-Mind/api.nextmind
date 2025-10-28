<?php

use App\Modules\Admin\Http\Controllers\UserCountController;

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
