<?php

namespace App\Modules\HelpDesk\Actions\Ticket;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\HelpDesk\Models\Ticket;
use App\Modules\HelpDesk\Models\TicketStatus;

class StoreTicketAction
{
    public function execute(array $data)
    {
        if (empty($data['requester_id'])) {
            $data['requester_id'] = $data['opened_by_id'];
        }

        if (empty($data['ticket_status_id'])) {
            $openStatus = TicketStatus::whereName('Open')->first();
            $data['ticket_status_id'] = $openStatus->id;
        }

        return Ticket::create($data);
    }
}
