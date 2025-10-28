<?php

namespace App\Modules\HelpDesk\Actions\TicketCategory;

use App\Modules\HelpDesk\Models\TicketCategory;

class IndexTicketCategoryAction
{
    public function execute()
    {
        return TicketCategory::query()
            ->with(['subcategories'])
            ->orderBy('position')
            ->get();
    }
}
