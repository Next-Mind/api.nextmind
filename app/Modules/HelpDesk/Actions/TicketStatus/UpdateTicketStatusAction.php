<?php

namespace App\Modules\HelpDesk\Actions\TicketStatus;

use App\Modules\HelpDesk\Models\TicketStatus;
use Str;

class UpdateTicketStatusAction
{
    public function execute(TicketStatus $ticketStatus, array $data): TicketStatus
    {
        if (array_key_exists('name', $data)) {
            $data['slug'] = Str::slug($data['name']);
        }
        $ticketStatus->fill($data)->save();
        return $ticketStatus;
    }
}
