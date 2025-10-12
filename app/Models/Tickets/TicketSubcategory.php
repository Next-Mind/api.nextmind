<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketSubcategory extends Model
{
    /** @use HasFactory<\Database\Factories\Tickets\TicketSubcategoryFactory> */
    use HasUuids, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_subcategory_id');
    }
    
}
