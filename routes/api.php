<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Users\MeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthGoogleTokenController;
use App\Http\Controllers\Users\UserPhoneController;

/**
 * ROTAS DE AUTENTICAÇÃO COM PREIFXO /auth/google
 */
Route::prefix('/auth/google')->group(function () {
    
    //Rota responsável por autenticar o usuário através do ID Token do Google, 
    //esta informação é obtida com SDKs próprios de cada plataforma que consumir esta API
    //Basicamente só comparamos se o ID Token que foi passado é valido e recuperamos/cadastramos o usuário em nosso banco de dados
    //Utilizamos o middleware 'ensureClientHeader' para garantir que terá o header 'X-Client' na requisição, esta informação será
    //utilizada para atribuir um nome ao token Sanctum que será gerado.
    Route::post('/token',AuthGoogleTokenController::class)->middleware('ensureClientHeader');
    
});


/**
 * ROTAS PROTEGIDAS PELO MIDDLEWARE DO SANCTUM
 */
Route::middleware('auth:sanctum')->group(function(){
    
    Route::get('/me',[MeController::class,'show'])->middleware('auth:sanctum')->name('users.me');

    Route::get('/users/phone/{userPhone}',[UserPhoneController::class,'show'])->name('users.phone.show');
    Route::post('/users/phone',[UserPhoneController::class,'store'])->name('users.phone');
    Route::put('/users/phone/{userPhone}',[UserPhoneController::class,'update'])->name('users.phone.update');
    Route::delete('/users/phone/{userPhone}',[UserPhoneController::class,'destroy'])->name('users.phone.destroy');
});




/**
 * ROTAS RESPONSÁVEIS PELO FLUXO DE VERIFICAÇÃO DE E-MAIL DO USUÁRIO
 */
Route::name('verification.')->group(function(){

    //Rotas que necessitam de autenticação do usuário
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/email/verify/notice',[EmailVerificationController::class,'notice'])->name('notice');
        Route::get('/email/verify/verification-notification',[EmailVerificationController::class,'send'])->name('send');
    });

    //Rota responsável pela verificação de email, não necessita de autenticação
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verify');
});


