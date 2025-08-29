<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\User\MeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthGoogleTokenController;


Route::prefix('/auth/google')->group(function () {
    
    //Rota responsável por autenticar o usuário através do ID Token do Google, 
    //esta informação é obtida com SDKs próprios de cada plataforma que consumir esta API
    //Basicamente só comparamos se o ID Token que foi passado é valido e recuperamos/cadastramos o usuário em nosso banco de dados
    //Utilizamos o middleware 'ensureClientHeader' para garantir que terá o header 'X-Client' na requisição, esta informação será
    //utilizada para atribuir um nome ao token Sanctum que será gerado.
    Route::post('/token',AuthGoogleTokenController::class)->middleware('ensureClientHeader');
    
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/me',[MeController::class,'show'])->middleware('auth:sanctum');
});

Route::name('verification.')->group(function(){
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/email/verify/notice',[EmailVerificationController::class,'notice'])->name('notice');
        Route::get('/email/verify/verification-notification',[EmailVerificationController::class,'send'])->name('send');
    });
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verify');
});


