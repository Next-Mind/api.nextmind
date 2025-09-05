<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    /** @use HasFactory<\Database\Factories\Posts\PostCategoryFactory> */
    use HasUuids,HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug'
    ];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
