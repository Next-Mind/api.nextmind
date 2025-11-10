<?php

namespace App\Modules\Students\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Users\Models\User;

class StudentProfile extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ra',
        'course',
        'bio',
    ];

    public function student()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
