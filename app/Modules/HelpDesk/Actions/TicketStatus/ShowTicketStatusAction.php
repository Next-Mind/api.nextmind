<?php

namespace App\Modules\HelpDesk\Actions\TicketStatus;

use App\Modules\HelpDesk\Models\TicketStatus;

class ShowTicketStatusAction
{
    public function execute(TicketStatus $ticketStatus): TicketStatus
    {
        return $ticketStatus;
    }
}
