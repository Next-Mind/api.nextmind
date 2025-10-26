<?php

namespace App\Modules\Psychologists\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;

class PsychologistDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $disk = config('filesystems.default');
        $path = optional($this->userFile)->path;

        $expiresAt = now()->addMinutes(10);

        $temporaryUrl = URL::temporarySignedRoute(
            'psychologists.documents.file',
            $expiresAt,
            ['document' => $this->getKey()]
        );

        return [
            'id'               => $this->id,
            'type'             => $this->type,
            'status'           => $this->status,
            'reviewed_by'      => $this->whenLoaded('reviewer', fn() => ['id' => $this->reviewer->id, 'name' => $this->reviewer->name]),
            'reviewed_at'      => $this->reviewed_at,
            'rejection_reason' => $this->rejection_reason,
            'created_at'       => $this->created_at,
            'temporary_url'    => $temporaryUrl
        ];
    }
}
