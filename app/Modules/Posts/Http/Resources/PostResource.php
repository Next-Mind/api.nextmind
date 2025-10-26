<?php

namespace App\Modules\Posts\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'author' => $this->whenLoaded('author', fn() => ['id' => $this->author->id, 'name' => $this->author->name, 'photo_url' => $this->author->photo_url, 'speciality' => optional($this->author->psychologistProfile)->speciality]),
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'language' => $this->language,
            'reading_time' => $this->reading_time,
            'like_count' => $this->like_count,
            'visibility' => $this->visibility,
            'body' => $this->body,

        ];
    }
}
