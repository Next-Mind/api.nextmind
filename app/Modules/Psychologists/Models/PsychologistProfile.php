<?php

namespace App\Modules\Psychologists\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PsychologistProfile extends Model
{
    use HasUuids, HasFactory;

    public const STATUS_APPROVED = 'approved';


    protected $fillable = [
        'user_id',
        'crp',
        'speciality',
        'bio',
        'status',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejection_reason',
        'verified_at'
    ];

    public function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at'  => 'datetime',
            'rejected_at'  => 'datetime',
            'verified_at'  => 'datetime',
        ];
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents()
    {
        return $this->hasMany(PsychologistDocument::class);
    }

    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
}
