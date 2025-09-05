<?php

namespace App\Models\Users;

use App\Models\User;
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

    public const REQUIRED_TYPES = ['crp_card','id_front','id_back','proof_of_address'];

    public function psychologistOwner(){
        return $this->belongsTo(PsychologistProfile::class,'psychologist_profile_id');
    }

     public function reviewer(){
        return $this->belongsTo(User::class,'reviewed_by');
    }

    public function userFile(){
        return $this->BelongsTo(UserFile::class,'user_file_id');
    }
}
