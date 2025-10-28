<?php

namespace App\Modules\HelpDesk\Actions\TicketSubcategory;

use App\Modules\HelpDesk\Models\TicketSubcategory;

class ShowTicketSubcategoryAction
{
    public function execute(TicketSubcategory $subcategory): TicketSubcategory
    {
        return $subcategory;
    }
}
