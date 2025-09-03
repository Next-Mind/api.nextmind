<?php

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'postal_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'country',
        'is_primary',
    ];

    public function casts()
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}


