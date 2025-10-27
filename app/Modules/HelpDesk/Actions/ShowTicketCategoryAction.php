<?php

namespace App\Modules\HelpDesk\Actions;

use App\Modules\HelpDesk\Models\TicketCategory;

class ShowTicketCategoryAction
{
    public function execute(TicketCategory $ticketCategory)
    {
        return $ticketCategory;
    }
}
