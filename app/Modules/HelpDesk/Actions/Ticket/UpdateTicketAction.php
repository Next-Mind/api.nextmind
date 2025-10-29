<?php

namespace App\Modules\HelpDesk\Actions\Ticket;

use App\Modules\Users\Models\User;;
use Illuminate\Support\Facades\Auth;
use App\Modules\HelpDesk\Models\Ticket;

class UpdateTicketAction
{
    public function execute(array $data, Ticket $ticket)
    {
        $ticket->fill($data)->save();
        return $ticket;
    }
}
