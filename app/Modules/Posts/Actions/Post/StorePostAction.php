<?php

namespace App\Modules\Posts\Actions\Post;

use App\Modules\Users\Models\User;

class StorePostAction
{
    public function execute(array $data, User $author)
    {
        // TODO: handle image upload and set image_url accordingly
        $data['image_url'] = $data['image_url'] ?? 'seila';
        return $author->posts()->create($data);
    }
}

