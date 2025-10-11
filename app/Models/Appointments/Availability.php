<?php

namespace App\Models\Appointments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Domain\Appointments\Enums\AvailabilityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    /** @use HasFactory<\Database\Factories\Appointments\AvailabilityFactory> */
    use HasUuids, HasFactory;
    
    protected $guarded = [
        
    ];
    
    protected $casts = [
        'date' => 'datetime',
        'status' => AvailabilityStatus::class,
    ];
    
    public function scopeForPsychologist(Builder $q, String $psychologistId): Builder {
        return $q->where('user_id', $psychologistId);
    }
    
    public function scopeBetween(Builder $q, \DateTimeInterface $start, \DateTimeInterface $end): Builder {
        return $q->whereBetween('date_availability', [$start, $end]);
    }
    
    public function scopeAvailable(Builder $q): Builder {
        return $q->where('status', AvailabilityStatus::Available);
    }
    
    
    public function psychologist() {
        return $this->belongsTo(User::class);
    }
    
    public function free(bool $persist = true): static
    {
        $this->status = AvailabilityStatus::Available;
        
        $this->reserved_by = null;
        
        if ($persist) {
            $this->save();
        }
        
        return $this;
    }
}
