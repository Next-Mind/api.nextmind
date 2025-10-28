<?php

namespace App\Modules\HelpDesk\Http\Resources;

use App\Modules\Users\Http\Resources\UserSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'ticket_id'  => $this->ticket_id,
            'sequence'   => $this->seq,
            'body'       => $this->body,
            'author' => UserSummaryResource::make(
                $this->whenLoaded('author')
            ),
        ];
    }
}
