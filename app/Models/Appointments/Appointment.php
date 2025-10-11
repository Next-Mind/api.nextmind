<?php

namespace App\Models\Appointments;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\Appointments\AppointmentFactory> */
    use HasUuids, HasFactory;
    
    protected $guarded = [];
    
    public function availability()
    {
        return $this->belongsTo(Availability::class, 'availability_id');
    }
    
    public function patient()
    {
        // nome mais explícito para user_id
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }
    public function scopeOfPsychologist($query, string $psychologistId)
    {
        return $query->where('psychologist_id', $psychologistId);
    }
    
    public function scopeOfPatient($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
    public function schedule()
    {
        $this->status = 'scheduled';
        return tap($this)->save();
    }
    
    public function complete()
    {
        $this->status = 'completed';
        return tap($this)->save();
    }
    
    public function cancel()
    {
        $this->status = 'canceled';
        return tap($this)->save();
    }
    
    public function markNoShow()
    {
        $this->status = 'no_show';
        return tap($this)->save();
    }
}
