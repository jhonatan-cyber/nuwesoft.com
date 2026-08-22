<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs('blog.show') || $request->is('api/*'), $this->content),
            'category' => $this->category,
            'tags' => $this->tags ?? [],
            'cover_image' => $this->cover_image,
            'author_name' => $this->author_name,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->toISOString(),
            'reading_time_minutes' => $this->calculateReadingTime(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'url' => route('blog.show', $this->slug),
        ];
    }

    /**
     * Estimate reading time in minutes based on word count (230 WPM).
     */
    protected function calculateReadingTime(): ?int
    {
        if (! $this->content) {
            return null;
        }

        $wordCount = str_word_count(strip_tags($this->content));

        return max(1, (int) ceil($wordCount / 230));
    }
}
