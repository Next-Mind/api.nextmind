<?php

namespace App\Modules\Posts\Actions\Post;

use App\Modules\Posts\Models\Post;
use App\Modules\Users\Models\User;

class IndexPostAction
{
    public function execute(array $queryParams = [], ?User $user = null)
    {
        $term    = trim((string) ($queryParams['search'] ?? ''));
        $summary = (bool) ($queryParams['summary'] ?? false);

        $query = Post::query();

        if ($user?->can('posts.view.any') || $user?->can('posts.manage.any')) {
            // full access
        } elseif ($user?->can('posts.view.self')) {
            $query->where(function ($q) use ($user) {
                $q->where('visibility', Post::VISIBILITY_PUBLIC)
                    ->orWhere('author_id', $user->getKey());
            });
        } else {
            $query->where('visibility', Post::VISIBILITY_PUBLIC);
        }

        if ($term !== '') {
            $like = "%{$term}%";
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('subtitle', 'like', $like)
                    ->orWhereHas('author', fn($qa) => $qa->where('name', 'like', $like))
                    ->orWhereHas('category', fn($qc) => $qc->where('name', 'like', $like));
            });
        }

        if ($summary) {
            $query->select([
                'id',
                'title',
                'subtitle',
                'image_url',
                'author_id',
                'post_category_id',
                'visibility',
                'created_at',
            ])->with([
                'author:id,name,photo_url',
                'category:id,name,slug',
            ]);
        } else {
            $query->with([
                'author:id,name,photo_url',
                'category:id,name,slug',
                'author.psychologistProfile:user_id,id,speciality',
            ]);
        }

        return $query->latest()->simplePaginate(15);
    }
}

