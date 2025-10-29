<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HelpDesk\Actions\TicketCategory\DestroyTicketCategoryAction;
use App\Modules\HelpDesk\Actions\TicketCategory\IndexTicketCategoryAction;
use App\Modules\HelpDesk\Actions\TicketCategory\StoreTicketCategoryAction;
use App\Modules\HelpDesk\Actions\TicketCategory\UpdateTicketCategoryAction;
use App\Modules\HelpDesk\Http\Resources\TicketCategoryResource;
use App\Modules\HelpDesk\Models\TicketCategory;
use App\Modules\HelpDesk\Http\Requests\StoreTicketCategoryRequest;
use App\Modules\HelpDesk\Http\Requests\UpdateTicketCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketCategoryController extends Controller
{
    public function index(Request $request, IndexTicketCategoryAction $action)
    {
        //Gate::authorize('viewAny', TicketCategory::class);
        $ticketCategories = $action->execute();
        return TicketCategoryResource::collection($ticketCategories);
    }

    public function show(TicketCategory $ticketsCategory)
    {
        Gate::authorize('view', $ticketsCategory);
        return new TicketCategoryResource($ticketsCategory);
    }

    public function store(StoreTicketCategoryRequest $request, StoreTicketCategoryAction $action)
    {
        Gate::authorize('create', TicketCategory::class);
        $data = $request->validated();
        $category = $action->execute($data);
        return new TicketCategoryResource($category);
    }

    public function update(UpdateTicketCategoryRequest $request, TicketCategory $ticketsCategory, UpdateTicketCategoryAction $action)
    {
        Gate::authorize('update', $ticketsCategory);
        $data = $request->validated();
        $ticketsCategory = $action->execute($data, $ticketsCategory);
        return new TicketCategoryResource($ticketsCategory);
    }

    public function destroy(TicketCategory $ticketsCategory, DestroyTicketCategoryAction $action)
    {
        Gate::authorize('destroy', $ticketsCategory);
        $action->execute($ticketsCategory);
        return response()->json([], 204);
    }
}
