<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

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
