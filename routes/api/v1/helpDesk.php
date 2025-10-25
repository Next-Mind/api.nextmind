<?php


use App\Http\Controllers\HelpDesk\TicketCategoryController;
use App\Http\Controllers\Helpdesk\TicketController;
use App\Http\Controllers\Helpdesk\TicketStatusController;
use App\Http\Controllers\Helpdesk\TicketMessageController;
use App\Http\Controllers\Helpdesk\TicketSubcategoryController;

// Route::prefix('tickets')->middleware("auth:sanctum")->group(function () {
//     Route::apiResource("categories",TicketCategoryController::class);
// });

Route::middleware('auth:sanctum')->group(function () {

    // Tickets CRUD
    Route::apiResource('tickets', TicketController::class);

    // Mensagens do ticket
    Route::get   ('tickets/{ticket}/messages',        [TicketMessageController::class, 'index']);
    Route::post  ('tickets/{ticket}/messages',        [TicketMessageController::class, 'store']);
    Route::delete('tickets/{ticket}/messages/{message}', [TicketMessageController::class, 'destroy']);

    // Tabelas auxiliares
    Route::apiResource('tickets/categories',    TicketCategoryController::class);
    Route::apiResource('tickets/subcategories', TicketSubcategoryController::class);
    Route::apiResource('tickets/statuses',      TicketStatusController::class);

});