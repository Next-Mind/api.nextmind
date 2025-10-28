<?php

namespace App\Modules\HelpDesk\Http\Controllers;

use App\Modules\HelpDesk\Exceptions\TicketMessageNotBelongsToTicketException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Modules\HelpDesk\Models\Ticket;
use App\Modules\HelpDesk\Models\TicketMessage;
use App\Modules\HelpDesk\Http\Resources\TicketMessageResource;
use App\Modules\HelpDesk\Http\Requests\StoreTicketMessageRequest;
use App\Modules\HelpDesk\Actions\TicketMessage\IndexTicketMessageAction;
use App\Modules\HelpDesk\Actions\TicketMessage\StoreTicketMessageAction;
use App\Modules\Users\Models\User;

class TicketMessageController extends Controller
{

    public function index(Request $request, Ticket $ticket, IndexTicketMessageAction $action)
    {
        Gate::authorize('view', $ticket);
        $messages = $action->execute($ticket);
        return TicketMessageResource::collection($messages);
    }

    public function store(StoreTicketMessageRequest $request, Ticket $ticket, StoreTicketMessageAction $action)
    {
        Gate::authorize('update', $ticket);
        /**
         * @var User
         */
        $user = $request->user();
        $validated = $request->validated();
        $message = $action->execute(
            $ticket,
            $user,
            $validated
        );
        $message->load('author');
        return new TicketMessageResource($message);
    }

    public function destroy(Request $request, Ticket $ticket, TicketMessage $message)
    {
        Gate::authorize('delete', $ticket);
        if ($message->ticket_id !== $ticket->id) {
            throw new TicketMessageNotBelongsToTicketException();
        }
        $message->delete();
        return response()->noContent();
    }
}
