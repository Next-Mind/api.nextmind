<?php

namespace App\Modules\HelpDesk\Actions\Ticket;

use App\Modules\HelpDesk\Models\Ticket;

class ShowTicketAction
{
    public function execute(Ticket $ticket, array $relations)
    {
        $ticket->load($relations);
        return $ticket;
    }
}
