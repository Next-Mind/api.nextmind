<?php

namespace App\Modules\HelpDesk\Actions\TicketSubcategory;

use App\Modules\HelpDesk\Models\TicketSubcategory;
use Illuminate\Support\Str;

class StoreTicketSubcategoryAction
{
    public function execute(array $data): TicketSubcategory
    {
        $data['slug'] = Str::slug($data['name']);
        if (!isset($data['position'])) {
            $data['position'] = (TicketSubcategory::max('position') ?? 0) + 1;
        }
        return TicketSubcategory::create($data);
    }
}
