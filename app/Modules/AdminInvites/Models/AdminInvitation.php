<?php

namespace App\Modules\AdminInvites\Models;

use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AdminInvitation extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'email',
        'invited_by',
        'token',
        'expires_at',
        'accepted_at',
        'declined_at',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function getIsExpiredAttribute()
    {
        return now()->greaterThan($this->expires_at);
    }

    public function getIsUsedAttribute()
    {
        return filled($this->accepted_at) || filled($this->declined_at);
    }

    public function scopeOpen($q)
    {
        return $q->whereNull('accepted_at')->whereNull('declined_at')->where('expires_at', '>', now());
    }
}
