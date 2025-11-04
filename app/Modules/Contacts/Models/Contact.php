<?php

namespace App\Modules\Contacts\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Users\Models\User;

class Contact extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'owner_id',
        'contact_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function contactUser()
    {
        return $this->belongsTo(User::class, 'contact_id');
    }
}
