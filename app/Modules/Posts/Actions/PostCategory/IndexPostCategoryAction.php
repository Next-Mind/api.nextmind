<?php

namespace App\Modules\Posts\Actions\PostCategory;

use App\Modules\Posts\Models\PostCategory;

class IndexPostCategoryAction
{
    public function execute()
    {
        return PostCategory::all();
    }
}

