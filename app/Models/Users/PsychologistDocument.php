<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PsychologistDocument extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'psychologist_profile_id',
        'user_file_id',
        'type',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason'
    ];

    public function psychologistOwner(){
        return $this->belongsTo(PsychologistProfile::class,'psychologist_profile_id');
    }

    public function userFile(){
        return $this->BelongsTo(UserFile::class,'user_file_id');
    }
}
