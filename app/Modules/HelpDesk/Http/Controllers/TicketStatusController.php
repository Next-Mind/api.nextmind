<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Modules\HelpDesk\Models\Ticket;
use App\Http\Controllers\Controller;
use App\Modules\HelpDesk\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketStatusController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);

        $statuses = TicketStatus::orderBy('name')->get();

        return response()->json($statuses);
    }

    public function store(Request $request)
    {
        Gate::authorize('update', Ticket::firstOrNew());

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_closed'   => ['required', 'boolean'], // exemplo comum
        ]);

        $status = TicketStatus::create($data);

        return response()->json($status, 201);
    }

    public function show(Request $request, TicketStatus $ticketStatus)
    {
        Gate::authorize('viewAny', Ticket::class);

        return response()->json($ticketStatus);
    }

    public function update(Request $request, TicketStatus $ticketStatus)
    {
        Gate::authorize('update', Ticket::firstOrNew());

        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'nullable'],
            'is_closed'   => ['sometimes', 'boolean'],
        ]);

        $ticketStatus->fill($data)->save();

        return response()->json($ticketStatus);
    }

    public function destroy(Request $request, TicketStatus $ticketStatus)
    {
        Gate::authorize('delete', Ticket::firstOrNew());

        $ticketStatus->delete();

        return response()->json([
            'message' => 'Status deleted',
        ]);
    }
}
