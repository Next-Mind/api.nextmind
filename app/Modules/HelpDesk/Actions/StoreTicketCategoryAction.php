<?php

namespace App\Modules\HelpDesk\Actions;

use App\Modules\HelpDesk\Models\TicketCategory;
use App\Modules\HelpDesk\Exceptions\DuplicateCategoryNameException;

class StoreTicketCategoryAction
{
    public function execute(array $data)
    {
        if (TicketCategory::where('name', $data['name'])->exists()) {
            throw new DuplicateCategoryNameException();
        }

        return TicketCategory::create($data);
    }
}
