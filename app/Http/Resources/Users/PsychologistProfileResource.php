<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PsychologistDocumentResource;

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
            'psychologist' => $this->whenLoaded('psychologist',fn()=>$this->psychologist->name),
            'crp' => $this->crp,
            'speciality' => $this->speciality,
            'bio' => $this->bio,
            'status' => $this->status,
            'verified_at' => $this->verified_at,
            'documents' => $this->whenLoaded('documents',fn()=> PsychologistDocumentResource::collection($this->documents)),
        ];
    }
}
