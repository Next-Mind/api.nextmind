<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Users\MeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PsychologistDocumentController;
use App\Http\Controllers\Users\UserAddressController;
use App\Http\Controllers\Users\UserPhoneController;


/**
 * Rotas de autenticação e registro
 */
include 'auth.php';

/**
 * Rotas de admin
 */
include 'admin.php';

/**
 * Helpers importantes
 */
include 'helpers.php';






//----------------------------------------------------------------------------------------------

/**
 * ROTAS PROTEGIDAS PELO MIDDLEWARE DO SANCTUM
*/
Route::middleware('auth:sanctum')->group(function(){
    
    Route::get('users/me',[MeController::class,'show'])->middleware('auth:sanctum')->name('users.me');

    //CRUD DE TELEFONES DO USUÁRIO
    Route::apiResource('users.phones',UserPhoneController::class)
        ->scoped(['user' => 'id', 'phone' => 'id']);

    //CRUD DE ENDEREÇOS DO USUÁRIO
    Route::apiResource('users.addresses',UserAddressController::class)
        ->scoped(['user' => 'id', 'address' => 'id']);

    Route::post('/teste',[PsychologistDocumentController::class,'store']);
});



//----------------------------------------------------------------------------------------------




