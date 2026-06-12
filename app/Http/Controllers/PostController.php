<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'is_published' => 'boolean',
            'author_name' => 'nullable|string|max:255',
        ]);

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

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'is_published' => 'boolean',
            'author_name' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['is_published'] && !$post->published_at) {
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

    public function publicIndex()
    {
        $posts = Post::published()
            ->latest('published_at')
            ->get();

        return Inertia::render('Blog', [
            'posts' => $posts,
        ]);
    }

    public function publicShow(Post $post)
    {
        if (!$post->is_published) {
            abort(404);
        }

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->take(3)
            ->get();

        return Inertia::render('BlogPost', [
            'post' => $post,
            'related' => $related,
        ]);
    }
}
