<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Modules\HelpDesk\Models\Ticket;
use App\Modules\HelpDesk\Models\TicketStatus;
use App\Modules\HelpDesk\Http\Resources\TicketStatusResource;
use App\Modules\HelpDesk\Http\Requests\StoreTicketStatusRequest;
use App\Modules\HelpDesk\Http\Requests\UpdateTicketStatusRequest;
use App\Modules\HelpDesk\Actions\TicketStatus\ShowTicketStatusAction;
use App\Modules\HelpDesk\Actions\TicketStatus\IndexTicketStatusAction;
use App\Modules\HelpDesk\Actions\TicketStatus\StoreTicketStatusAction;
use App\Modules\HelpDesk\Actions\TicketStatus\UpdateTicketStatusAction;
use App\Modules\HelpDesk\Actions\TicketStatus\DestroyTicketStatusAction;

class TicketStatusController extends Controller
{
    public function index(IndexTicketStatusAction $action)
    {
        Gate::authorize('viewAny', TicketStatus::class);
        $statuses = $action->execute();
        return TicketStatusResource::collection($statuses);
    }

    public function store(StoreTicketStatusRequest $request, StoreTicketStatusAction $action)
    {
        Gate::authorize('create', TicketStatus::class);
        $status = $action->execute($request->validated());
        return new TicketStatusResource($status);
    }

    public function show(TicketStatus $ticketsStatus, ShowTicketStatusAction $action)
    {
        Gate::authorize('viewAny', TicketStatus::class);
        $status = $action->execute($ticketsStatus);
        return new TicketStatusResource($status);
    }

    public function update(UpdateTicketStatusRequest $request, TicketStatus $ticketsStatus, UpdateTicketStatusAction $action)
    {
        Gate::authorize('update', $ticketsStatus);
        $status = $action->execute($ticketsStatus, $request->validated());
        return new TicketStatusResource($status);
    }

    public function destroy(TicketStatus $ticketsStatus, DestroyTicketStatusAction $action)
    {
        Gate::authorize('delete', $ticketsStatus);
        $action->execute($ticketsStatus);
        return response()->noContent();
    }
}
