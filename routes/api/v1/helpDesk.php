<?php

use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\Posts\PostCategoryController;
use App\Http\Controllers\TicketCategoryController;

Route::prefix('tickets')->middleware("auth:sanctum")->group(function () {
    Route::apiResource("categories",TicketCategoryController::class);
});