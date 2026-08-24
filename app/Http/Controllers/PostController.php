<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $posts = Post::latest()->paginate($perPage)->withQueryString();

        return Inertia::render('Dashboard/Posts/Index', [
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Posts/PostForm');
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        Post::create($validated);

        return Redirect::route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Dashboard/Posts/PostForm', [
            'post' => $post,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['is_published'] && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return Redirect::route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return Redirect::route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function publicIndex(Request $request)
    {
        $query = Post::published()->latest('published_at');

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($search = $request->get('search')) {
            $search = trim($search);

            // PostgreSQL full-text search with ranking
            $query->whereRaw(
                "to_tsvector('spanish', coalesce(title, '') || ' ' || coalesce(content, '') || ' ' || coalesce(excerpt, '')) @@ plainto_tsquery('spanish', ?)",
                [$search]
            )->orderByRaw(
                "ts_rank(to_tsvector('spanish', coalesce(title, '') || ' ' || coalesce(content, '') || ' ' || coalesce(excerpt, '')), plainto_tsquery('spanish', ?)) DESC",
                [$search]
            );
        }

        $posts = $query->paginate($request->input('per_page', 9))->withQueryString();

        return Inertia::render('Blog', [
            'posts' => $posts,
            'filters' => $request->only(['category', 'search']),
        ]);
    }

    public function publicShow(Post $post)
    {
        if (! $post->is_published) {
            abort(404);
        }

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->take(3)
            ->get();

        return Inertia::render('BlogPost', [
            'post' => new PostResource($post),
            'related' => PostResource::collection($related),
        ]);
    }
}
