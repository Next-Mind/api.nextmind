<?php

use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\Posts\PostCategoryController;

Route::prefix('posts')->middleware("auth:sanctum")->group(function () {
    Route::apiResource("categories",PostCategoryController::class);
});
Route::apiResource("posts",PostController::class)->middleware('auth:sanctum');