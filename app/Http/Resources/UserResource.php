<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Users\PsychologistProfileResource;

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
            'email_verified' => $this->hasVerifiedEmail(),
            'roles' => $this->getRoleNames(),
            'cpf' => $this->cpf,
            'birth_date' => $this->birth_date,
            'photo_url' => $this->photo_url,
            'student_profile' => $this->when($this->hasRole('student'),new StudentProfileResource($this->whenLoaded('studentProfile'))),
            'psychologist_profile' => $this->when($this->hasRole('psychologist'),new PsychologistProfileResource($this->whenLoaded('psychologistProfile'))),
            'addresses' => UserAddressResource::collection($this->whenLoaded('addresses')),
            'phones' => UserPhoneResource::collection($this->whenLoaded('phones')),
        ];
    }
}
