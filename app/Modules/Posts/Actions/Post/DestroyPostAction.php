<?php

namespace App\Modules\Posts\Actions\Post;

use App\Modules\Posts\Models\Post;

class DestroyPostAction
{
    public function execute(Post $post): void
    {
        $post->delete();
    }
}

