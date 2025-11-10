<?php

namespace App\Modules\Contacts\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'owner_id'   => $this->owner_id,
            'contact_id' => $this->contact_id,
            'contact'    => $this->whenLoaded('contactUser', function () {
                if (! $this->contactUser) {
                    return null;
                }

                return [
                    'id' => $this->contactUser->id,
                    'name' => $this->contactUser->name,
                    'photo_url' => $this->contactUser->photo_url,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}
