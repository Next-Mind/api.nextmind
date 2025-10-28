<?php

namespace App\Modules\HelpDesk\Actions\TicketSubcategory;

use App\Modules\HelpDesk\Models\TicketSubcategory;

class IndexTicketSubcategoryAction
{
    public function execute()
    {
        return TicketSubcategory::orderBy('position')
            ->orderBy('name')
            ->get();
    }
}
