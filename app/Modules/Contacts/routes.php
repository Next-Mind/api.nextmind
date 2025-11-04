<?php

use App\Modules\Contacts\Http\Controllers\ContactController;
use App\Modules\Contacts\Http\Controllers\ContactCandidateController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('contacts/candidates', [ContactCandidateController::class, 'index']);
    Route::apiResource('contacts', ContactController::class);
});
