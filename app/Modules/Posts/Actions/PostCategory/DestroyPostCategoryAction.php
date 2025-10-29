<?php

namespace App\Modules\Posts\Actions\PostCategory;

use App\Modules\Posts\Models\PostCategory;

class DestroyPostCategoryAction
{
    public function execute(PostCategory $category): void
    {
        $category->delete();
    }
}

