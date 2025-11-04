<?php

namespace App\Modules\Posts\Actions\PostCategory;

use Illuminate\Support\Str;
use App\Modules\Posts\Models\PostCategory;

class StorePostCategoryAction
{
    public function execute(array $data)
    {
        $slug = Str::slug(title: $data['name'], language: 'pt');
        return PostCategory::create([
            'name' => $data['name'],
            'slug' => $slug,
        ]);
    }
}

