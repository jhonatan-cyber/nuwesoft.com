<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'desc' => $this->desc,
            'icon' => $this->icon,
            'project_url' => $this->project_url,
            'is_active' => $this->is_active,
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'images' => ProjectImageResource::collection($this->whenLoaded('images')),
            'cover_image' => $this->whenLoaded('images', function () {
                return $this->images->first()?->url;
            }),
            'url' => route('portafolio.show', $this->slug),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
