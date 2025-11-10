<?php

namespace App\Modules\Posts\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

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
        return $this->belongsTo(User::class, 'author_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id')->withTrashed();
    }
}
