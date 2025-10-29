<?php

namespace App\Modules\Posts\Actions\Post;

use App\Modules\Posts\Models\Post;

class UpdatePostAction
{
    public function execute(array $data, Post $post)
    {
        $post->update($data);
        return $post;
    }
}

