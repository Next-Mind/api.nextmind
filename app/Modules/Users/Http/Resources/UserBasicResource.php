<?php

namespace App\Modules\Users\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBasicResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url,
            'role' => $this->getRoleNames()->first(),
            'primary_phone' => $this->whenLoaded('primaryPhone', function () {
                if (!$this->primaryPhone) {
                    return null;
                }

                return [
                    'id' => $this->primaryPhone->id,
                    'label' => $this->primaryPhone->label,
                    'country_code' => $this->primaryPhone->country_code,
                    'area_code' => $this->primaryPhone->area_code,
                    'number' => $this->primaryPhone->number,
                    'is_whatsapp' => $this->primaryPhone->is_whatsapp,
                ];
            }),
        ];
    }
}
