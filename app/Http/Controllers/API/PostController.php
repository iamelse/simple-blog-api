<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('category', 'user')->latest()->paginate(10);
    }

    public function show(Post $post)
    {
        $post->load('category', 'user');

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'category' => $post->category,
            'user' => $post->user,
            'cover_url' => $post->getFirstMediaUrl('cover'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required', 'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'required|image|max:2048'
        ]);

        $data['user_id'] = auth()->id();
        $post = Post::create($data);

        if ($request->hasFile('cover')) {
            $post->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return response()->json($post->load('category'), 201);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->only('title', 'content', 'category_id');
        $post->update($data);

        if ($request->hasFile('cover')) {
            $post->clearMediaCollection('cover');
            $post->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return $post->load('category');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}
