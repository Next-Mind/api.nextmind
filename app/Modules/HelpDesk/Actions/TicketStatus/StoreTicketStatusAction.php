<?php

namespace App\Modules\HelpDesk\Actions\TicketStatus;

use App\Modules\HelpDesk\Models\TicketStatus;
use Str;

class StoreTicketStatusAction
{
    public function execute(array $data): TicketStatus
    {
        return TicketStatus::create([
            'name'       => $data['name'],
            'slug'       => Str::slug($data['name']),
            'position'   => $data['position'],
            'is_final'   => $data['is_final'],
        ]);
    }
}
