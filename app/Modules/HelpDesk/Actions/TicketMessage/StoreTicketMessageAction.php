<?php

namespace App\Modules\HelpDesk\Actions\TicketMessage;

use App\Modules\Users\Models\User;
use App\Modules\HelpDesk\Models\Ticket;
use App\Modules\HelpDesk\Models\TicketMessage;

class StoreTicketMessageAction
{
    public function execute(Ticket $ticket, User $author, array $payload): TicketMessage
    {
        $data = [
            'ticket_id'   => $ticket->id,
            'author_id'   => $author->id,
            'body'        => $payload['body']        ?? null,
            'is_internal' => $payload['is_internal'] ?? false,
        ];

        $data['seq'] = $this->nextSeqFor($ticket);

        return TicketMessage::create($data);
    }
    protected function nextSeqFor(Ticket $ticket): int
    {
        return (int) TicketMessage::where('ticket_id', $ticket->id)
            ->max('seq') + 1;
    }
}
