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
        // Include content unless we're on the blog index list page
        $path = trim((string) $request->path(), '/');
        $includeContent = $path !== 'blog';

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
        ];

        if ($includeContent) {
            $data['content'] = $this->content;
        }

        $data = array_merge($data, [
            'category' => $this->category instanceof \BackedEnum ? ($this->category->label() ?? $this->category->value) : $this->category,
            'tags' => $this->tags ?? [],
            'cover_image' => $this->cover_image,
            'author_name' => $this->author_name,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->toISOString(),
            'reading_time_minutes' => $this->calculateReadingTime(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'url' => route('blog.show', $this->slug),
        ]);

        return $data;
    }

    /**
     * Estimate reading time in minutes based on word count (230 WPM).
     */
    protected function calculateReadingTime(): ?int
    {
        if (! $this->content) {
            return null;
        }

        // Reemplaza tags por espacio para no concatenar palabras (ej: </p><div>)
        $text = preg_replace('/<[^>]+>/', ' ', $this->content);
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $text)));

        if ($text === '') {
            return null;
        }

        $wordCount = str_word_count($text);

        return max(1, (int) ceil($wordCount / 230));
    }
}
