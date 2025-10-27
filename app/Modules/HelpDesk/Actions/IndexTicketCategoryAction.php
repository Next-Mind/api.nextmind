<?php

namespace App\Modules\HelpDesk\Actions;

use App\Modules\HelpDesk\Models\TicketCategory;

class IndexTicketCategoryAction
{
    public function execute()
    {
        return TicketCategory::query()
            ->orderBy('position')
            ->get();
    }
}
