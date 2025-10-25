<?php

namespace App\Http\Controllers\Helpdesk;

use Illuminate\Http\Request;
use App\Models\Tickets\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Tickets\TicketSubcategory;

class TicketSubcategoryController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);

        $subs = TicketSubcategory::orderBy('name')->get();

        return response()->json($subs);
    }

    public function store(Request $request)
    {
        Gate::authorize('update', Ticket::firstOrNew());

        $data = $request->validate([
            'category_id'  => ['required','integer','exists:ticket_categories,id'],
            'name'         => ['required','string','max:255'],
            'description'  => ['nullable','string'],
        ]);

        $sub = TicketSubcategory::create($data);

        return response()->json($sub, 201);
    }

    public function show(Request $request, TicketSubcategory $ticketSubcategory)
    {
        Gate::authorize('viewAny', Ticket::class);

        return response()->json($ticketSubcategory);
    }

    public function update(Request $request, TicketSubcategory $ticketSubcategory)
    {
        Gate::authorize('update', Ticket::firstOrNew());

        $data = $request->validate([
            'category_id'  => ['sometimes','integer','exists:ticket_categories,id'],
            'name'         => ['sometimes','string','max:255'],
            'description'  => ['sometimes','string','nullable'],
        ]);

        $ticketSubcategory->fill($data)->save();

        return response()->json($ticketSubcategory);
    }

    public function destroy(Request $request, TicketSubcategory $ticketSubcategory)
    {
        Gate::authorize('delete', Ticket::firstOrNew());

        $ticketSubcategory->delete();

        return response()->json([
            'message' => 'Subcategory deleted',
        ]);
    }
}
