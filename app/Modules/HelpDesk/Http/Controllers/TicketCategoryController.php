<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HelpDesk\Models\TicketCategory;
use App\Modules\HelpDesk\Http\Requests\StoreTicketCategoryRequest;
use App\Modules\HelpDesk\Http\Requests\UpdateTicketCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketCategoryController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', TicketCategory::class);

        $q = TicketCategory::query()
            ->orderBy('position');

        return response()->json($q->get());
    }

    public function show(TicketCategory $ticketCategory)
    {
        Gate::authorize('view', $ticketCategory);
        $ticketCategory->loadMissing('subcategories');
        return response()->json($ticketCategory);
    }

    public function store(StoreTicketCategoryRequest $request)
    {
        Gate::authorize('create', TicketCategory::class);
        $data = $request->validated();

        $category = TicketCategory::create($data);

        return response()->json($category->load('subcategories'), 201);
    }

    public function update(UpdateTicketCategoryRequest $request, TicketCategory $ticketCategory)
    {

        //Gate::authorize('update',$ticketCategory);

        $data = $request->validated();

        $ticketCategory->update($data);

        return response()->json($ticketCategory->fresh());
    }

    public function destroy(Request $request, TicketCategory $ticketCategory)
    {
        Gate::authorize('destroy', $ticketCategory);
        $hasSubs    = $ticketCategory->subcategories()->exists();
        $hasTickets = $ticketCategory->tickets()->exists();

        if ($hasSubs || $hasTickets) {
            return response()->json([
                'message' => 'Não é possível excluir: há subcategorias e/ou tickets vinculados.'
            ], 422);
        }

        $ticketCategory->delete();

        return response()->json([], 204);
    }
}
