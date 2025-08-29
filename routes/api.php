<?php

use App\Http\Controllers\Auth\AuthGoogleTokenController;
use App\Http\Controllers\Auth\AuthGoogleWebClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/auth/google/redirect',[AuthGoogleWebClientController::class,'redirect']);
Route::get('/auth/google/callback',[AuthGoogleWebClientController::class,'callback']);

Route::post('/auth/google/token',AuthGoogleTokenController::class);