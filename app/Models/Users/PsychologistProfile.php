<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PsychologistProfile extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'crp',
        'speciality',
        'bio',
        'verified_at',
    ];

    public function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function psychologist(){
        return $this->belongsTo(User::class);
    }
}
