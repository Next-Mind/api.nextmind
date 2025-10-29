<?php

namespace App\Modules\HelpDesk\Actions\TicketMessage;

use App\Modules\HelpDesk\Models\Ticket;

class IndexTicketMessageAction
{
    public function execute(Ticket $ticket)
    {
        return $ticket->messages()
            ->with('author')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
