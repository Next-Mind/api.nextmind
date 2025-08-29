<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'is_email_verified' => $this->hasVerifiedEmail(),
            'cpf' => $this->cpf,
            'birth_date' => $this->birth_date,
            'photo_url' => $this->photo_url,
            'student_profile' => StudentProfileResource::collection($this->whenLoaded('studentProfile')),
            'psychologist_profile' => PsychologistProfileResource::collection($this->whenLoaded('psychologistProfile')),
            'addresses' => UserAddressResource::collection($this->whenLoaded('addresses')),
            'phones' => UserPhoneResource::collection($this->whenLoaded('phones')),
        ];
    }
}
