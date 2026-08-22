<?php

namespace Tests\Feature;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PostResourceTest extends TestCase
{
    use RefreshDatabase;

    private function createPost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'title' => 'Test Post Title',
            'slug' => 'test-post-title',
            'excerpt' => 'This is a test excerpt.',
            'content' => 'This is the full content of the post with enough words to test the reading time calculation. It needs to be fairly long to test the word count and ensure the reading time is calculated correctly based on two hundred and thirty words per minute.',
            'category' => 'TECHNICAL',
            'tags' => ['php', 'laravel'],
            'cover_image' => 'https://example.com/cover.jpg',
            'author_name' => 'Test Author',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ], $overrides));
    }

    // ── Field Presence ──────────────────────────────────

    public function test_resource_contains_all_expected_fields(): void
    {
        $post = $this->createPost();
        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $expectedKeys = [
            'id', 'title', 'slug', 'excerpt', 'content', 'category',
            'tags', 'cover_image', 'author_name', 'is_published',
            'published_at', 'reading_time_minutes', 'created_at',
            'updated_at', 'url',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array, "Missing key: {$key}");
        }
    }

    public function test_resource_returns_correct_values(): void
    {
        $post = $this->createPost([
            'title' => 'Laravel Best Practices',
            'slug' => 'laravel-best-practices',
            'category' => 'TECHNICAL',
            'author_name' => 'John Doe',
        ]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertEquals($post->id, $array['id']);
        $this->assertEquals('Laravel Best Practices', $array['title']);
        $this->assertEquals('laravel-best-practices', $array['slug']);
        $this->assertEquals('TECHNICAL', $array['category']);
        $this->assertEquals('John Doe', $array['author_name']);
        $this->assertTrue($array['is_published']);
    }

    // ── Slug-based URL ─────────────────────────────────

    public function test_resource_url_uses_slug_not_id(): void
    {
        $post = $this->createPost(['slug' => 'my-awesome-post']);
        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertStringContainsString('/blog/my-awesome-post', $array['url']);
        $this->assertStringNotContainsString('/blog/' . $post->id, $array['url']);
    }

    public function test_resource_url_matches_blog_show_route(): void
    {
        $post = $this->createPost(['slug' => 'hello-world']);
        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $expectedUrl = route('blog.show', 'hello-world');
        $this->assertEquals($expectedUrl, $array['url']);
    }

    // ── Reading Time Calculation ────────────────────────

    public function test_reading_time_returns_minutes_for_long_content(): void
    {
        // 460 words = 2 minutes at 230 WPM
        $content = str_repeat('word ', 460);
        $post = $this->createPost(['content' => $content]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertEquals(2, $array['reading_time_minutes']);
    }

    public function test_reading_time_returns_minimum_one_minute(): void
    {
        // 10 words should still show 1 minute
        $post = $this->createPost(['content' => 'This is a short post.']);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertEquals(1, $array['reading_time_minutes']);
    }

    public function test_reading_time_returns_null_when_no_content(): void
    {
        $post = $this->createPost(['content' => null]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertNull($array['reading_time_minutes']);
    }

    public function test_reading_time_returns_null_when_empty_content(): void
    {
        $post = $this->createPost(['content' => '']);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertNull($array['reading_time_minutes']);
    }

    public function test_reading_time_strips_html_tags_before_counting(): void
    {
        // 230 words wrapped in HTML = 1 minute
        $words = implode(' ', array_fill(0, 230, 'word'));
        $content = "<p>{$words}</p><div><strong>Bold text</strong></div>";
        $post = $this->createPost(['content' => $content]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertEquals(1, $array['reading_time_minutes']);
    }

    public function test_reading_time_rounds_up_fractions(): void
    {
        // 231 words = 1.004... → ceil → 2 minutes
        $content = str_repeat('word ', 231);
        $post = $this->createPost(['content' => $content]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertEquals(2, $array['reading_time_minutes']);
    }

    public function test_reading_time_for_large_article(): void
    {
        // 2300 words = 10 minutes
        $content = str_repeat('word ', 2300);
        $post = $this->createPost(['content' => $content]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertEquals(10, $array['reading_time_minutes']);
    }

    // ── Tags Handling ───────────────────────────────────

    public function test_tags_returned_as_array(): void
    {
        $post = $this->createPost(['tags' => ['laravel', 'php', 'vue']]);
        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertIsArray($array['tags']);
        $this->assertCount(3, $array['tags']);
        $this->assertEquals(['laravel', 'php', 'vue'], $array['tags']);
    }

    public function test_tags_defaults_to_empty_array_when_null(): void
    {
        $post = $this->createPost(['tags' => null]);
        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertIsArray($array['tags']);
        $this->assertEmpty($array['tags']);
    }

    // ── Date Formatting ─────────────────────────────────

    public function test_dates_are_iso8601_formatted(): void
    {
        $post = $this->createPost([
            'published_at' => now()->subDays(5),
        ]);

        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        // Verify ISO 8601 format (ends with Z or +00:00)
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $array['published_at']);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $array['created_at']);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $array['updated_at']);
    }

    public function test_published_at_is_null_when_not_published(): void
    {
        $post = $this->createPost(['published_at' => null]);
        $resource = new PostResource($post);
        $array = $resource->toArray(new Request);

        $this->assertNull($array['published_at']);
    }

    // ── Content Conditional Inclusion ───────────────────

    public function test_content_not_included_on_blog_index_route(): void
    {
        $post = $this->createPost(['content' => 'Full content here']);

        // Simulate a request to blog.index (not blog.show)
        $request = Request::create('/blog', 'GET');
        $resource = new PostResource($post);
        $array = $resource->toArray($request);

        $this->assertArrayNotHasKey('content', $array);
    }

    public function test_content_included_on_blog_show_route(): void
    {
        $post = $this->createPost(['content' => 'Full content here']);

        // Simulate a request to blog.show
        $request = Request::create('/blog/my-post', 'GET');
        $request->setRouteResolver(fn () => new class
        {
            public function current()
            {
                return new class
                {
                    public function getName(): string
                    {
                        return 'blog.show';
                    }
                };
            }
        });

        $resource = new PostResource($post);
        $array = $resource->toArray($request);

        $this->assertArrayHasKey('content', $array);
        $this->assertEquals('Full content here', $array['content']);
    }
}
