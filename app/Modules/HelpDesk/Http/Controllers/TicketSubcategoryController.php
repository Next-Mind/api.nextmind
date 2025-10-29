<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Modules\HelpDesk\Models\Ticket;
use App\Modules\HelpDesk\Models\TicketSubcategory;
use App\Modules\HelpDesk\Http\Resources\TicketSubcategoryResource;
use App\Modules\HelpDesk\Http\Requests\StoreTicketSubcategoryRequest;
use App\Modules\HelpDesk\Http\Requests\UpdateTicketSubcategoryRequest;
use App\Modules\HelpDesk\Actions\TicketSubcategory\ShowTicketSubcategoryAction;
use App\Modules\HelpDesk\Actions\TicketSubcategory\IndexTicketSubcategoryAction;
use App\Modules\HelpDesk\Actions\TicketSubcategory\StoreTicketSubcategoryAction;
use App\Modules\HelpDesk\Actions\TicketSubcategory\UpdateTicketSubcategoryAction;
use App\Modules\HelpDesk\Actions\TicketSubcategory\DestroyTicketSubcategoryAction;

class TicketSubcategoryController extends Controller
{
    public function index(IndexTicketSubcategoryAction $action)
    {
        Gate::authorize('viewAny', Ticket::class);
        $subcategories = $action->execute();
        return TicketSubcategoryResource::collection($subcategories);
    }

    public function store(StoreTicketSubcategoryRequest $request, StoreTicketSubcategoryAction $action)
    {
        Gate::authorize('update', Ticket::class);
        $subcategory = $action->execute($request->validated());
        return new TicketSubcategoryResource($subcategory);
    }

    public function show(TicketSubcategory $ticketsSubcategory, ShowTicketSubcategoryAction $action)
    {
        Gate::authorize('viewAny', Ticket::class);
        $subcategory = $action->execute($ticketsSubcategory);
        return new TicketSubcategoryResource($subcategory);
    }

    public function update(
        UpdateTicketSubcategoryRequest $request,
        TicketSubcategory $ticketsSubcategory,
        UpdateTicketSubcategoryAction $action
    ) {
        Gate::authorize('update', Ticket::class);
        $subcategory = $action->execute($ticketsSubcategory, $request->validated());
        return new TicketSubcategoryResource($subcategory);
    }

    public function destroy(
        TicketSubcategory $ticketsSubcategory,
        DestroyTicketSubcategoryAction $action
    ) {
        Gate::authorize('delete', Ticket::class);

        $action->execute($ticketsSubcategory);

        return response()->noContent();
    }
}
