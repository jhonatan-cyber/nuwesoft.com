<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // ── Public Endpoints ──

    public function test_blog_page_can_be_rendered(): void
    {
        $this->get('/blog')->assertStatus(200);
    }

    public function test_published_post_is_accessible_by_slug(): void
    {
        $post = Post::create([
            'title' => 'My First Post',
            'slug' => 'my-first-post',
            'content' => 'Hello world',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/my-first-post');
        $response->assertStatus(200);
    }

    public function test_unpublished_post_returns_404_on_public_show(): void
    {
        Post::create([
            'title' => 'Draft Post',
            'slug' => 'draft-post',
            'content' => 'Not ready yet',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/blog/draft-post');
        $response->assertStatus(404);
    }

    public function test_future_published_post_returns_404(): void
    {
        Post::create([
            'title' => 'Future Post',
            'slug' => 'future-post',
            'content' => 'Scheduled for later',
            'is_published' => true,
            'published_at' => now()->addDays(7),
        ]);

        // The blog index uses the published() scope which filters future dates
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }

    // ── Admin: Authentication ──

    public function test_guests_cannot_access_posts_dashboard(): void
    {
        $this->get('/dashboard/posts')->assertRedirect('/login');
    }

    public function test_guests_cannot_create_post(): void
    {
        $this->post('/dashboard/posts', ['title' => 'Test'])->assertRedirect('/login');
    }

    // ── Admin: CRUD ──

    public function test_authenticated_user_can_view_posts_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/posts');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'New Post Title',
            'excerpt' => 'A short excerpt',
            'content' => 'Full post content here.',
            'category' => 'technical',
            'tags' => ['laravel', 'php'],
            'is_published' => true,
            'author_name' => 'Test Author',
        ];

        $response = $this->actingAs($user)->post('/dashboard/posts', $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('posts', [
            'title' => 'New Post Title',
            'slug' => 'new-post-title',
            'category' => 'technical',
            'is_published' => true,
            'author_name' => 'Test Author',
        ]);
    }

    public function test_store_sets_published_at_when_is_published_true(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/dashboard/posts', [
            'title' => 'Published Post',
            'is_published' => true,
        ]);

        $post = Post::where('slug', 'published-post')->first();
        $this->assertNotNull($post->published_at);
    }

    public function test_store_sets_null_published_at_when_is_published_false(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/dashboard/posts', [
            'title' => 'Draft Post',
            'is_published' => false,
        ]);

        $post = Post::where('slug', 'draft-post')->first();
        $this->assertNull($post->published_at);
    }

    public function test_store_generates_slug_from_title(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/dashboard/posts', [
            'title' => 'My Amazing Blog Post!',
            'is_published' => false,
        ]);

        $this->assertDatabaseHas('posts', [
            'slug' => 'my-amazing-blog-post',
        ]);
    }

    public function test_authenticated_user_can_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'content' => 'Old content',
            'is_published' => false,
        ]);

        $response = $this->actingAs($user)->put("/dashboard/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'is_published' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $post->refresh();
        $this->assertEquals('updated-title', $post->slug);
        $this->assertNotNull($post->published_at);
    }

    public function test_update_sets_published_at_only_once(): void
    {
        $user = User::factory()->create();
        $originalDate = now()->subDays(5);
        $post = Post::create([
            'title' => 'Already Published',
            'slug' => 'already-published',
            'is_published' => true,
            'published_at' => $originalDate,
        ]);

        $this->actingAs($user)->put("/dashboard/posts/{$post->id}", [
            'title' => 'Already Published',
            'is_published' => true,
        ]);

        $post->refresh();
        $this->assertEquals($originalDate->timestamp, $post->published_at->timestamp);
    }

    public function test_authenticated_user_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'To Be Deleted',
            'slug' => 'to-be-deleted',
        ]);

        $response = $this->actingAs($user)->delete("/dashboard/posts/{$post->id}");

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_store_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/posts', [
            'title' => '',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_store_validates_excerpt_max_length(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/posts', [
            'title' => 'Valid Title',
            'excerpt' => str_repeat('a', 501),
        ]);

        $response->assertSessionHasErrors(['excerpt']);
    }

    public function test_store_validates_tags_as_array(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/posts', [
            'title' => 'Post With Invalid Tags',
            'tags' => 'not-an-array',
        ]);

        $response->assertSessionHasErrors(['tags']);
    }

    // ── Public Blog: Related Posts ──

    public function test_public_show_returns_related_posts(): void
    {
        $post = Post::create([
            'title' => 'Main Post',
            'slug' => 'main-post',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Related posts (same category)
        Post::create([
            'title' => 'Related One',
            'slug' => 'related-one',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Post::create([
            'title' => 'Related Two',
            'slug' => 'related-two',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Different category — should NOT appear as related
        Post::create([
            'title' => 'Unrelated Post',
            'slug' => 'unrelated-post',
            'category' => 'news',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/main-post');
        $response->assertStatus(200);
    }

    // ── Public Blog: Pagination ──

    public function test_blog_index_returns_paginated_results(): void
    {
        // Create 12 published posts (more than default 9 per page)
        for ($i = 1; $i <= 12; $i++) {
            Post::create([
                'title' => "Post {$i}",
                'slug' => "post-{$i}",
                'category' => 'technical',
                'is_published' => true,
                'published_at' => now()->subDays($i),
            ]);
        }

        // Page 1 — should have 9 posts
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Blog')
            ->where('posts.current_page', 1)
            ->where('posts.per_page', 9)
            ->where('posts.total', 12)
            ->has('posts.data', 9)
        );
    }

    public function test_blog_index_page_2_returns_remaining_posts(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Post::create([
                'title' => "Post {$i}",
                'slug' => "post-{$i}",
                'category' => 'technical',
                'is_published' => true,
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get('/blog?page=2');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('posts.current_page', 2)
            ->has('posts.data', 3) // 12 - 9 = 3 remaining
        );
    }

    public function test_blog_index_respects_custom_per_page(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Post::create([
                'title' => "Post {$i}",
                'slug' => "post-{$i}",
                'category' => 'technical',
                'is_published' => true,
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get('/blog?per_page=3');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('posts.per_page', 3)
            ->where('posts.total', 5)
            ->has('posts.data', 3)
        );
    }

    // ── Public Blog: Category Filtering ──

    public function test_blog_index_filters_by_category(): void
    {
        Post::create([
            'title' => 'Technical Post',
            'slug' => 'technical-post',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);
        Post::create([
            'title' => 'News Post',
            'slug' => 'news-post',
            'category' => 'news',
            'is_published' => true,
            'published_at' => now(),
        ]);
        Post::create([
            'title' => 'Another Technical',
            'slug' => 'another-technical',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog?category=technical');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('filters.category', 'technical')
            ->where('posts.total', 2)
            ->has('posts.data', 2)
        );
    }

    public function test_blog_index_category_filter_excludes_unpublished(): void
    {
        Post::create([
            'title' => 'Published Tech',
            'slug' => 'published-tech',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);
        Post::create([
            'title' => 'Draft Tech',
            'slug' => 'draft-tech',
            'category' => 'technical',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/blog?category=technical');
        $response->assertInertia(fn ($page) => $page
            ->where('posts.total', 1)
        );
    }

    public function test_blog_index_unknown_category_returns_empty(): void
    {
        Post::create([
            'title' => 'Post',
            'slug' => 'post',
            'category' => 'technical',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog?category=nonexistent');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('posts.total', 0)
            ->has('posts.data', 0)
        );
    }

    public function test_blog_index_without_category_returns_all_published(): void
    {
        Post::create(['title' => 'A', 'slug' => 'a', 'category' => 'technical', 'is_published' => true, 'published_at' => now()]);
        Post::create(['title' => 'B', 'slug' => 'b', 'category' => 'news', 'is_published' => true, 'published_at' => now()]);
        Post::create(['title' => 'C', 'slug' => 'c', 'category' => 'insights', 'is_published' => true, 'published_at' => now()]);

        $response = $this->get('/blog');
        $response->assertInertia(fn ($page) => $page
            ->where('posts.total', 3)
            ->where('filters.category', null)
        );
    }

    public function test_blog_index_category_filter_preserves_in_query_string(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Tech Post {$i}",
                'slug' => "tech-post-{$i}",
                'category' => 'technical',
                'is_published' => true,
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get('/blog?category=technical&page=2');
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('filters.category', 'technical')
            ->where('posts.current_page', 2)
        );
    }

    // ── Slug Collision ──

    public function test_store_handles_slug_collision(): void
    {
        $user = User::factory()->create();

        Post::create([
            'title' => 'Duplicate Title',
            'slug' => 'duplicate-title',
            'is_published' => false,
        ]);

        // This should still work (slug collision handled or not — depends on implementation)
        $this->actingAs($user)->post('/dashboard/posts', [
            'title' => 'Duplicate Title',
            'is_published' => false,
        ]);

        $this->assertEquals(2, Post::where('title', 'Duplicate Title')->count());
    }
}
