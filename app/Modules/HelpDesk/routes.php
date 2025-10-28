<?php

use Illuminate\Support\Facades\Route;
use App\Modules\HelpDesk\Http\Controllers\TicketController;
use App\Modules\HelpDesk\Http\Controllers\TicketStatusController;
use App\Modules\HelpDesk\Http\Controllers\TicketMessageController;
use App\Modules\HelpDesk\Http\Controllers\TicketCategoryController;
use App\Modules\HelpDesk\Http\Controllers\TicketSubcategoryController;


// Route::prefix('tickets')->middleware("auth:sanctum")->group(function () {
//     Route::apiResource("categories",TicketCategoryController::class);
// });

Route::middleware('auth:sanctum')->group(function () {

    // Tickets CRUD
    Route::apiResource('tickets', TicketController::class);

    // Mensagens do ticket
    Route::apiResource('tickets.messages',    TicketMessageController::class);


    // Tabelas auxiliares
    Route::apiResource('tickets-categories',    TicketCategoryController::class);
    Route::apiResource('tickets-subcategories', TicketSubcategoryController::class);
    Route::apiResource('tickets-statuses',      TicketStatusController::class);
});
