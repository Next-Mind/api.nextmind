<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFile extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'purpose',
        'original_name',
        'path',
        'mime_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
