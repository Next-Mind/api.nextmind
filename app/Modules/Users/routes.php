<?php

use App\Modules\Users\Http\Controllers\MeController;
use App\Modules\Users\Http\Controllers\UserPhoneController;
use App\Modules\Users\Http\Controllers\UserAddressController;

/**
 * ROTAS PROTEGIDAS PELO MIDDLEWARE DO SANCTUM
 */
Route::middleware('auth:sanctum')->group(function () {

    Route::get('users/me', [MeController::class, 'show'])->middleware('auth:sanctum')->name('users.me');

    //CRUD DE TELEFONES DO USUÁRIO
    Route::apiResource('users.phones', UserPhoneController::class)
        ->scoped(['user' => 'id', 'phone' => 'id']);

    //CRUD DE ENDEREÇOS DO USUÁRIO
    Route::apiResource('users.addresses', UserAddressController::class)
        ->scoped(['user' => 'id', 'address' => 'id']);
});
