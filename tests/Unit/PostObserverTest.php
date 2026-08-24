<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Observers\PostObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostObserverTest extends TestCase
{
    use RefreshDatabase;

    // ── Slug Generation (via static method) ──

    public function test_generate_unique_slug_from_title(): void
    {
        $slug = PostObserver::generateUniqueSlug('My First Blog Post');
        $this->assertEquals('my-first-blog-post', $slug);
    }

    public function test_generate_unique_slug_handles_special_characters(): void
    {
        $slug = PostObserver::generateUniqueSlug('¿Cómo Funciona Laravel?');
        $this->assertEquals('como-funciona-laravel', $slug);
    }

    public function test_generate_unique_slug_avoids_existing(): void
    {
        Post::create([
            'title' => 'Existing Post',
            'slug' => 'existing-post',
            'content' => 'Content',
            'is_published' => false,
        ]);

        $slug = PostObserver::generateUniqueSlug('Existing Post');
        $this->assertEquals('existing-post-1', $slug);
    }

    public function test_generate_unique_slug_handles_multiple_duplicates(): void
    {
        Post::create(['title' => 'Triple', 'slug' => 'triple', 'content' => '1', 'is_published' => false]);
        Post::create(['title' => 'Triple', 'slug' => 'triple-1', 'content' => '2', 'is_published' => false]);

        $slug = PostObserver::generateUniqueSlug('Triple');
        $this->assertEquals('triple-2', $slug);
    }

    public function test_generate_unique_slug_ignores_specific_id(): void
    {
        $post = Post::create([
            'title' => 'Existing Post',
            'slug' => 'existing-post',
            'content' => 'Content',
            'is_published' => false,
        ]);

        // When ignoring the same post's ID, the slug is unique
        $slug = PostObserver::generateUniqueSlug('Existing Post', $post->id);
        $this->assertEquals('existing-post', $slug);
    }

    public function test_generate_unique_slug_returns_empty_for_empty_title(): void
    {
        $slug = PostObserver::generateUniqueSlug('');
        $this->assertEquals('', $slug);
    }

    // ── Published Scope ──

    public function test_published_scope_filters_unpublished_posts(): void
    {
        Post::create([
            'title' => 'Published',
            'slug' => 'published',
            'content' => 'Yes',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Post::create([
            'title' => 'Draft',
            'slug' => 'draft',
            'content' => 'No',
            'is_published' => false,
            'published_at' => null,
        ]);

        $published = Post::published()->get();
        $this->assertCount(1, $published);
        $this->assertEquals('Published', $published->first()->title);
    }

    public function test_published_scope_filters_future_posts(): void
    {
        Post::create([
            'title' => 'Now',
            'slug' => 'now',
            'content' => 'Yes',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Post::create([
            'title' => 'Future',
            'slug' => 'future',
            'content' => 'No',
            'is_published' => true,
            'published_at' => now()->addDays(7),
        ]);

        $published = Post::published()->get();
        $this->assertCount(1, $published);
        $this->assertEquals('Now', $published->first()->title);
    }

    public function test_published_scope_excludes_null_published_at(): void
    {
        Post::create([
            'title' => 'No Date',
            'slug' => 'no-date',
            'content' => 'No',
            'is_published' => true,
            'published_at' => null,
        ]);

        $published = Post::published()->get();
        $this->assertCount(0, $published);
    }

    // ── Category Casting ──

    public function test_category_is_lowercased_on_set(): void
    {
        $post = Post::create([
            'title' => 'Category Test',
            'slug' => 'category-test',
            'content' => 'Content',
            'category' => 'TECHNICAL',
            'is_published' => false,
        ]);

        $this->assertEquals('technical', $post->category->value);
    }

    // ── Tags Casting ──

    public function test_tags_are_cast_to_array(): void
    {
        $post = Post::create([
            'title' => 'Tags Test',
            'slug' => 'tags-test',
            'content' => 'Content',
            'tags' => ['laravel', 'php'],
            'is_published' => false,
        ]);

        $this->assertIsArray($post->tags);
        $this->assertEquals(['laravel', 'php'], $post->tags);
    }

    public function test_tags_stored_as_json(): void
    {
        $post = Post::create([
            'title' => 'JSON Tags',
            'slug' => 'json-tags',
            'content' => 'Content',
            'tags' => ['vue', 'react'],
            'is_published' => false,
        ]);

        // Tags should be accessible as array via the cast
        $this->assertEquals(['vue', 'react'], $post->tags);
        // Verify it round-trips correctly from the database
        $fresh = $post->fresh();
        $this->assertEquals(['vue', 'react'], $fresh->tags);
    }

    // ── Is Active Casting ──

    public function test_is_published_is_cast_to_boolean(): void
    {
        $post = Post::create([
            'title' => 'Bool Test',
            'slug' => 'bool-test',
            'content' => 'Content',
            'is_published' => 1,
        ]);

        $this->assertIsBool($post->is_published);
        $this->assertTrue($post->is_published);
    }

    // ── Content Fields ──

    public function test_excerpt_can_be_null(): void
    {
        $post = Post::create([
            'title' => 'No Excerpt',
            'slug' => 'no-excerpt',
            'content' => 'Content',
            'is_published' => false,
        ]);

        $this->assertNull($post->excerpt);
    }

    public function test_author_name_can_be_set(): void
    {
        $post = Post::create([
            'title' => 'With Author',
            'slug' => 'with-author',
            'content' => 'Content',
            'author_name' => 'John Doe',
            'is_published' => false,
        ]);

        $this->assertEquals('John Doe', $post->author_name);
    }
}
