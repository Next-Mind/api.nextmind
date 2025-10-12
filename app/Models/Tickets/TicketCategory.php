<?php

namespace App\Models\Tickets;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCategory extends Model
{
    /** @use HasFactory<\Database\Factories\Tickets\TicketCategoryFactory> */
    use HasUuids, HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(TicketSubcategory::class, 'ticket_category_id');
    }
}
