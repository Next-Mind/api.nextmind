<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPhoneResource extends JsonResource
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
            'label' => $this->label,
            'country_code' => $this->country_code,
            'area_code' => $this->area_code,
            'number' => $this->number,
            'is_whatsapp' => $this->is_whatsapp,
            'is_primary' => $this->is_primary
        ];
    }
}
