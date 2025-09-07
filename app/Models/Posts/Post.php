<?php

namespace App\Models\Posts;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\Posts\PostFactory> */
    use HasUuids, HasFactory;

    const VISIBILITY_PUBLIC = "public";
    const VISIBILITY_PRIVATE = "private";

    protected $fillable = [
        'post_category_id',
        'author_id',
        'title',
        'subtitle',
        'image_url',
        'body',
        'language',
        'like_count',
        'reading_time',
        'visibility'
        
    ];

    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class,'post_category_id');
    }
}
