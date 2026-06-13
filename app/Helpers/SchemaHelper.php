<?php

namespace App\Helpers;

use App\Models\Project;
use App\Models\Post;

class SchemaHelper
{
    /**
     * Generate Organization JSON-LD Schema.
     */
    public static function organization(array $settings): array
    {
        $siteName = $settings['site_name'] ?? 'NUWESOFT';
        $logoUrl = $settings['logo_url'] ?? null;
        $tagline = $settings['tagline'] ?? '';
        $orgEmail = $settings['email'] ?? null;

        $socialLinks = array_filter([
            $settings['social_facebook'] ?? null,
            $settings['social_twitter'] ?? null,
            $settings['social_linkedin'] ?? null,
            $settings['social_github'] ?? null,
        ]);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'),
            'description' => $tagline,
        ];

        if ($logoUrl) {
            $schema['logo'] = $logoUrl;
        }

        if ($orgEmail) {
            $schema['contactPoint'] = [
                '@type' => 'ContactPoint',
                'email' => $orgEmail,
                'contactType' => 'customer service',
            ];
        }

        if (!empty($socialLinks)) {
            $schema['sameAs'] = array_values($socialLinks);
        }

        return $schema;
    }

    /**
     * Generate WebSite JSON-LD Schema.
     */
    public static function website(array $settings): array
    {
        $siteName = $settings['site_name'] ?? 'NUWESOFT';

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/blog?search={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Generate BreadcrumbList JSON-LD Schema.
     */
    public static function breadcrumb(array $crumbs): array
    {
        $items = [];
        foreach ($crumbs as $index => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Generate CreativeWork/Project JSON-LD Schema.
     */
    public static function project(Project $project): array
    {
        $images = $project->images->pluck('image_url')->toArray();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $project->name,
            'description' => $project->desc,
            'genre' => $project->category,
            'url' => route('portafolio.show', $project->id),
        ];

        if (!empty($images)) {
            $schema['image'] = $images;
        }

        return $schema;
    }

    /**
     * Generate BlogPosting/Article JSON-LD Schema.
     */
    public static function article(Post $post): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->excerpt ?? substr(strip_tags($post->content), 0, 160),
            'datePublished' => $post->created_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'url' => route('blog.show', $post->slug),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author?->name ?? 'Admin',
            ],
        ];

        if ($post->featured_image) {
            $schema['image'] = $post->featured_image;
        }

        return $schema;
    }
}
