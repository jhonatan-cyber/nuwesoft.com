<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->image_url,
            'image_url' => $this->image_url,
            'optimized_url' => $this->optimized_image_url,
            'optimized_image_url' => $this->optimized_image_url,
            'blur_url' => $this->blur_image_url ?? $this->image_url,
            'public_id' => $this->public_id,
            'alt' => $this->alt ?? $this->project?->name ?? null,
            'order_index' => $this->order_index,
        ];
    }
}
