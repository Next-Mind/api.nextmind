<?php

namespace App\Modules\HelpDesk\Actions\TicketStatus;

use App\Modules\HelpDesk\Models\TicketStatus;

class DestroyTicketStatusAction
{
    public function execute(TicketStatus $ticketStatus): void
    {
        $ticketStatus->delete();
    }
}
