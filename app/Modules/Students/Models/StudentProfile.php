<?php

namespace App\Modules\Students\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Users\Models\User;

class StudentProfile extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'ra',
        'course',
        'bio',
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
