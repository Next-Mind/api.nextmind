<?php

namespace App\Modules\Posts\Actions\PostCategory;

use App\Modules\Posts\Models\PostCategory;

class ShowPostCategoryAction
{
    public function execute(PostCategory $category)
    {
        return $category;
    }
}

