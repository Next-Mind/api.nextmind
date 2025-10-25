<?php

namespace App\Http\Controllers\Helpdesk;

use Illuminate\Http\Request;
use App\Models\Tickets\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Tickets\TicketMessage;

class TicketMessageController extends Controller
{
    /**
     * GET /tickets/{ticket}/messages
     */
    public function index(Request $request, Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $messages = $ticket->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * POST /tickets/{ticket}/messages
     */
    public function store(Request $request, Ticket $ticket)
    {
        // Se o user pode atualizar o ticket, ele pode participar da conversa.
        Gate::authorize('update', $ticket);

        $data = $request->validate([
            'message' => ['required','string'],
            // se você suporta anexos, etc, colocar aqui
        ]);

        $data['user_id']   = $request->user()->id;
        $data['ticket_id'] = $ticket->id;

        $msg = TicketMessage::create($data);

        return response()->json($msg, 201);
    }

    /**
     * DELETE /tickets/{ticket}/messages/{message}
     *
     * Vou usar mesma permissão de delete do ticket:
     * se o cara pode deletar o ticket, ele pode moderar/remover mensagens.
     * (Se quiser só permitir apagar a própria mensagem, dá pra mudar.)
     */
    public function destroy(Request $request, Ticket $ticket, TicketMessage $message)
    {
        Gate::authorize('delete', $ticket);

        // opcional: garantir que a mensagem pertence ao ticket
        if ($message->ticket_id !== $ticket->id) {
            return response()->json(['error' => 'Message does not belong to this ticket'], 422);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted',
        ]);
    }
}
