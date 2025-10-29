<?php

namespace App\Modules\HelpDesk\Actions\TicketStatus;

use App\Modules\HelpDesk\Models\TicketStatus;
use Illuminate\Support\Collection;

class IndexTicketStatusAction
{
    public function execute(): Collection
    {
        return TicketStatus::orderBy('name')->get();
    }
}
