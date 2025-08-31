<?php

namespace App\Models\Users;

use App\Models\User;
use App\Policies\Users\UserPhonePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(UserPhonePolicy::class)]
class UserPhone extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'country_code',
        'area_code',
        'number',
        'is_whatsapp',
        'is_primary',
    ];

    public function casts(): array
    {
        return [
            'is_whatsapp' => 'boolean',
            'is_primary' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
