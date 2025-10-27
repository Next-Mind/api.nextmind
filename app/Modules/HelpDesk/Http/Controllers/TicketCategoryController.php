<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HelpDesk\Actions\DestroyTicketCategoryAction;
use App\Modules\HelpDesk\Actions\IndexTicketCategoryAction;
use App\Modules\HelpDesk\Actions\StoreTicketCategoryAction;
use App\Modules\HelpDesk\Actions\UpdateTicketCategoryAction;
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

    public function show(TicketCategory $ticketCategory)
    {
        Gate::authorize('view', $ticketCategory);
        return new TicketCategoryResource($ticketCategory);
    }

    public function store(StoreTicketCategoryRequest $request, StoreTicketCategoryAction $action)
    {
        Gate::authorize('create', TicketCategory::class);
        $data = $request->validated();
        $category = $action->execute($data);
        return new TicketCategoryResource($category);
    }

    public function update(UpdateTicketCategoryRequest $request, TicketCategory $ticketCategory, UpdateTicketCategoryAction $action)
    {
        Gate::authorize('update', $ticketCategory);
        $data = $request->validated();
        $ticketCategory = $action->execute($data, $ticketCategory);
        return new TicketCategoryResource($ticketCategory);
    }

    public function destroy(TicketCategory $ticketCategory, DestroyTicketCategoryAction $action)
    {
        Gate::authorize('destroy', $ticketCategory);
        $action->execute($ticketCategory);
        return response()->json([], 204);
    }
}
