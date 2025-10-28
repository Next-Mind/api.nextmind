<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Modules\HelpDesk\Models\Ticket;
use App\Modules\HelpDesk\Http\Resources\TicketResource;
use App\Modules\HelpDesk\Actions\Ticket\IndexTicketAction;
use App\Modules\HelpDesk\Actions\Ticket\ShowTicketAction;
use App\Modules\HelpDesk\Actions\Ticket\StoreTicketAction;
use App\Modules\HelpDesk\Http\Requests\IndexTicketRequest;
use App\Modules\HelpDesk\Http\Requests\StoreTicketRequest;
use App\Modules\HelpDesk\Actions\Ticket\UpdateTicketAction;
use App\Modules\HelpDesk\Http\Requests\UpdateTicketRequest;

class TicketController extends Controller
{
    /**
     * GET /tickets
     */
    public function index(IndexTicketRequest $request, IndexTicketAction $action)
    {
        Gate::authorize('viewAny', Ticket::class);
        $queryParams = $request->validatedWithDefaults();
        $relations = $request->query("relations", []);
        $tickets = $action->execute($queryParams, null, $relations);
        return TicketResource::collection($tickets);
    }

    /**
     * POST /tickets
     */
    public function store(StoreTicketRequest $request, StoreTicketAction $action)
    {
        Gate::authorize('create', Ticket::class);
        $data = $request->validated();
        $data['opened_by_id'] = Auth::id();
        $ticket = $action->execute($data);
        return new TicketResource($ticket);
    }

    /**
     * GET /tickets/{ticket}
     */
    public function show(Request $request, Ticket $ticket, ShowTicketAction $action)
    {
        Gate::authorize('view', $ticket);
        $relations = $request->query("relations", []);
        $ticket = $action->execute($ticket, $relations);
        return new TicketResource($ticket);
    }

    /**
     * PUT/PATCH /tickets/{ticket}
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket, UpdateTicketAction $action)
    {
        Gate::authorize('update', $ticket);
        $data = $request->validated();
        $ticket = $action->execute($data, $ticket);
        return new TicketResource($ticket);
    }

    /**
     * DELETE /tickets/{ticket}
     */
    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);
        $ticket->delete();
        return response()->noContent();
    }
}
