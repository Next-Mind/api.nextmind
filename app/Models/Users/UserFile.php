<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'purpose',
        'original_name',
        'path',
        'mime_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
