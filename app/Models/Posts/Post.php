<?php

namespace App\Models\Posts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\Posts\PostFactory> */
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class,'post_category_id');
    }
}
