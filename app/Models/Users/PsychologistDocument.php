<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class PsychologistDocument extends Model
{

    protected $fillable = [
        'id',
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
}
