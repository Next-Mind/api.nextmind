<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Tickets\TicketCategory;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\StoreTicketCategoryRequest;
use App\Http\Requests\UpdateTicketCategoryRequest;

class TicketCategoryController extends Controller
{
    public function index(Request $request) {
        Gate::authorize('viewAny',TicketCategory::class);
        $withSubs = $request->query('with_subs');
        
        $q = TicketCategory::query()
        ->when($withSubs, fn($qr) => $qr->with('subcategories'))
        ->whereIsActive(true)
        ->orderBy('position');
        
        return response()->json($q->get());
    }
    
    public function show(TicketCategory $ticketCategory) {
        Gate::authorize('view',$ticketCategory);
        $ticketCategory->loadMissing('subcategories');
        return response()->json($ticketCategory);
    }
    
    public function store(StoreTicketCategoryRequest $request) {
        Gate::authorize('create', TicketCategory::class);
        $data = $request->validated();
        
        $category = TicketCategory::create($data);
        
        return response()->json($category->load('subcategories'),201);
    }
    
    public function update(UpdateTicketCategoryRequest $request, TicketCategory $ticketCategory){
        
        Gate::authorize('update',$ticketCategory);
        
        $data = $request->validated();
        
        $ticketCategory->update($data);
        
        return response()->json($ticketCategory->fresh()->load('subcategories'));
    }
    
    public function destroy(Request $request, TicketCategory $ticketCategory){
        Gate::authorize('destroy',$ticketCategory);
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
