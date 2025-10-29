<?php

namespace App\Modules\HelpDesk\Actions\TicketSubcategory;

use App\Modules\HelpDesk\Models\TicketSubcategory;

class DestroyTicketSubcategoryAction
{
    public function execute(TicketSubcategory $subcategory): void
    {
        $subcategory->delete();
    }
}
