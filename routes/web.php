<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Http\Controllers\LoginController;
use App\Modules\Auth\Http\Controllers\AuthGoogleWebClientController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('ensureClientHeader')
    ->withoutMiddleware(['web'])
    ->post('/login', [LoginController::class, 'loginStateless'])
    ->name('login.stateless');

Route::middleware('ensureClientHeader')
    ->post('/login/web', [LoginController::class, 'loginStateful'])
    ->name('login.stateful');


Route::prefix('/auth')->group(function () {
    //Rota responsável por redirecionar o usuário a página de autenticação do Google
    //Utilizado no Painel WEB
    Route::get('/google/redirect', [AuthGoogleWebClientController::class, 'redirect']);


    //Rota responsável por receber os dados do Google do usuário autenticado
    //Utilizado no Painel WEB
    Route::get('/google/callback', [AuthGoogleWebClientController::class, 'callback']);
});
