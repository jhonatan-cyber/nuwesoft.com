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
            'category' => $this->category instanceof \BackedEnum ? $this->category->value : $this->category,
            'desc' => $this->desc,
            'icon' => $this->icon,
            'project_url' => $this->project_url,
            'is_active' => $this->is_active,
            'technologies' => $this->whenLoaded('technologies', fn () => TechnologyResource::collection($this->technologies), []),
            'images' => $this->whenLoaded('images', fn () => ProjectImageResource::collection($this->images), []),
            'cover_image' => $this->whenLoaded('images', fn () => $this->images->first()?->image_url, null),
            'cover_image_optimized' => $this->whenLoaded('images', fn () => $this->images->first()?->optimized_image_url, null),
            'cover_image_blur' => $this->whenLoaded('images', fn () => $this->images->first()?->blur_image_url, null),
            'url' => route('portafolio.show', $this->slug),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
