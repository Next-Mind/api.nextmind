<?php

use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;

Route::prefix('tickets')->middleware("auth:sanctum")->group(function () {
    Route::apiResource("categories",TicketCategoryController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class);
});