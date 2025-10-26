<?php

use App\Modules\Posts\Http\Controllers\PostController;
use App\Modules\Posts\Http\Controllers\PostCategoryController;

Route::prefix('posts')->middleware("auth:sanctum")->group(function () {
    Route::apiResource("categories", PostCategoryController::class);
});
Route::apiResource("posts", PostController::class)->middleware('auth:sanctum');
