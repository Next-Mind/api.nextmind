<?php

namespace App\Modules\HelpDesk\Actions;

use App\Modules\HelpDesk\Models\TicketCategory;
use App\Modules\HelpDesk\Exceptions\CategoryInUseException;

class DestroyTicketCategoryAction
{
    public function execute(TicketCategory $category): void
    {
        $hasSubs    = $category->subcategories()->exists();
        $hasTickets = $category->tickets()->exists();

        if ($hasSubs || $hasTickets) {
            throw new CategoryInUseException();
        }

        $category->delete();
    }
}
