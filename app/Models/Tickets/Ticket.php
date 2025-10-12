<?php

namespace App\Models\Tickets;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Tickets\TicketStatus;
use App\Models\Tickets\TicketCategory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tickets\TicketSubcategory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\Tickets\TicketFactory> */
    use HasUuids, HasFactory, SoftDeletes;


    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            if (is_null($ticket->ticket_number)) {
                $ticket->ticket_number = static::nextNumber();
            }
        });
    }

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'ticket_number'         => 'int',
        'comments_count'        => 'int',
        'attachments_count'     => 'int',
        'first_response_due_at' => 'datetime',
        'resolution_due_at'     => 'datetime',
        'resolved_at'           => 'datetime',
        'closed_at'             => 'datetime',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
    ];

    
    public static function nextNumber(): int
    {

        return DB::transaction(function ()  {
            // Cria ou incrementa
            $sql = "
                INSERT INTO ticket_sequences (`current_value`)
                VALUES (0)
                ON DUPLICATE KEY UPDATE `current_value` = LAST_INSERT_ID(`current_value` + 1)
            ";

            DB::statement($sql);

            $newVal = (int) DB::selectOne('SELECT LAST_INSERT_ID() AS v')->v;

            return $newVal > 0 ? $newVal : 1;
        });
    }


     public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(TicketSubcategory::class, 'ticket_subcategory_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }
}
