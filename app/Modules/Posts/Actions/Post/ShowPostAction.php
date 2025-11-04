<?php

namespace App\Modules\Posts\Actions\Post;

use App\Modules\Posts\Models\Post;

class ShowPostAction
{
    public function execute(Post $post)
    {
        return $post;
    }
}

