<?php

namespace App\Modules\Posts\Actions\PostCategory;

use Illuminate\Support\Str;
use App\Modules\Posts\Models\PostCategory;

class UpdatePostCategoryAction
{
    public function execute(array $data, PostCategory $category)
    {
        $slug = Str::slug(title: $data['name'], language: 'pt');
        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
        ]);
        return $category;
    }
}

