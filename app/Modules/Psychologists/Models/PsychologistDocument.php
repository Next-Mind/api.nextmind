<?php

namespace App\Modules\Psychologists\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Users\Models\UserFile;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PsychologistDocument extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'psychologist_profile_id',
        'user_file_id',
        'type',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason'
    ];

    public const REQUIRED_TYPES = ['crp_card', 'id_front', 'id_back', 'proof_of_address'];

    public function psychologistOwner()
    {
        return $this->belongsTo(PsychologistProfile::class, 'psychologist_profile_id')->withTrashed();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by')->withTrashed();
    }

    public function userFile()
    {
        return $this->belongsTo(UserFile::class, 'user_file_id')->withTrashed();
    }
}
