<?php

namespace App\Models\Tickets;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketMessage extends Pivot
{
    /** @use HasFactory<\Database\Factories\Tickets\TicketMessageFactory> */
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'ticket_messages';

    protected $guarded = ['id', 'created_at','deleted_at'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'seq'               => 'int',
        'is_internal'       => 'bool',
        'edited_at'         => 'datetime',
        'created_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (TicketMessage $msg) {
            if (!$msg->ticket_id) {
                throw new \InvalidArgumentException('ticket_id é obrigatório para TicketMessage.');
            }

            if (is_null($msg->seq)) {
                $msg->seq = DB::transaction(function () use ($msg) {
                    $max = DB::table('ticket_messages')
                        ->where('ticket_id', $msg->ticket_id)
                        ->lockForUpdate()
                        ->max('seq');

                    return (int)$max + 1; 
                });
            }
        });
    }

    // Relacionamentos
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    // Scopes úteis
    public function scopePublic($q)
    {
        return $q->where('is_internal', false);
    }

    public function scopeInternal($q)
    {
        return $q->where('is_internal', true);
    }
}
