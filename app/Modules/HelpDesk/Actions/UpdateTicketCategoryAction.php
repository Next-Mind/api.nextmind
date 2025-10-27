<?php

namespace App\Modules\HelpDesk\Actions;

use App\Modules\HelpDesk\Models\TicketCategory;

class UpdateTicketCategoryAction
{
    public function execute(array $data, TicketCategory $ticketCategory)
    {
        $ticketCategory->update($data);
        return $ticketCategory->fresh();
    }
}
