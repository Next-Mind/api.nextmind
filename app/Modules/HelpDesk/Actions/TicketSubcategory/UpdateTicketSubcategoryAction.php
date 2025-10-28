<?php

namespace App\Modules\HelpDesk\Actions\TicketSubcategory;

use App\Modules\HelpDesk\Models\TicketSubcategory;
use Illuminate\Support\Str;

class UpdateTicketSubcategoryAction
{
    public function execute(TicketSubcategory $subcategory, array $data): TicketSubcategory
    {
        if (!empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $subcategory->fill($data)->save();

        return $subcategory;
    }
}
