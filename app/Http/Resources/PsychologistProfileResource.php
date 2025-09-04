<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PsychologistProfileResource extends JsonResource
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
            'crp' => $this->crp,
            'speciality' => $this->speciality,
            'bio' => $this->bio,
            'status' => $this->status,
            'verified_at' => $this->verified_at
        ];
    }
}
