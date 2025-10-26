<?php

namespace App\Modules\Posts\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'subtitle'  => $this->subtitle,
            'image_url' => $this->image_url,
            'category'  => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],
            'author'    => [
                'id'        => $this->author?->id,
                'name'      => $this->author?->name,
                'photo_url' => $this->author?->photo_url,
            ],
        ];
    }
}
