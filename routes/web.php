<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Http\Controllers\AuthGoogleWebClientController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('/auth')->group(function () {
    //Rota responsável por redirecionar o usuário a página de autenticação do Google
    //Utilizado no Painel WEB
    Route::get('/google/redirect', [AuthGoogleWebClientController::class, 'redirect']);


    //Rota responsável por receber os dados do Google do usuário autenticado
    //Utilizado no Painel WEB
    Route::get('/google/callback', [AuthGoogleWebClientController::class, 'callback']);
});
