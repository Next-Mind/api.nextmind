<?php

namespace App\Modules\HelpDesk\Http\Resources;

use App\Modules\Users\Http\Resources\UserSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'opened_by' => UserSummaryResource::make($this->whenLoaded('openedBy')),
            'requester' => UserSummaryResource::make($this->whenLoaded('requester')),
            'category' => TicketCategorySummaryResource::make($this->whenLoaded('category')),
            'subcategory' => TicketSubcategorySummaryResource::make($this->whenLoaded('subcategory')),
            'status' => TicketStatusResource::make($this->whenLoaded('status')),
        ];
    }
}
