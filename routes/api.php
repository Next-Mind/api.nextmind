<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Users\MeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthGoogleTokenController;
use App\Http\Controllers\Auth\PsychologistDocumentController;
use App\Http\Controllers\Auth\PsychologistRegisterController;
use App\Http\Controllers\Users\UserAddressController;
use App\Http\Controllers\Users\UserPhoneController;

/**
 * ROTAS DE AUTENTICAÇÃO COM PREIFXO /auth/google
 */
Route::prefix('/auth')->group(function () {
    
    //Rota responsável por autenticar o usuário através do ID Token do Google, 
    //esta informação é obtida com SDKs próprios de cada plataforma que consumir esta API
    //Basicamente só comparamos se o ID Token que foi passado é valido e recuperamos/cadastramos o usuário em nosso banco de dados
    //Utilizamos o middleware 'ensureClientHeader' para garantir que terá o header 'X-Client' na requisição, esta informação será
    //utilizada para atribuir um nome ao token Sanctum que será gerado.
    Route::post('google/token',AuthGoogleTokenController::class)->middleware('ensureClientHeader');

    Route::post('login/admin',AdminLoginController::class)->middleware('ensureClientHeader');


    Route::post('register/psychologist',PsychologistRegisterController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('register/psychologist/upload',[PsychologistDocumentController::class,'store']);
    });

    
    
});

//----------------------------------------------------------------------------------------------

/**
 * ROTAS PROTEGIDAS PELO MIDDLEWARE DO SANCTUM
 */
Route::middleware('auth:sanctum')->group(function(){


    // //ROTAS DE CADASTRO PSICÓLOGO
    // Route::prefix('me/psychologist')->group(function(){
    //     Route::post('profile',fn()=>'HelloWorld');
    // });

    
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


