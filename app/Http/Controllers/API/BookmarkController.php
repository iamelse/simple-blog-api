<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();
        $user->bookmarks()->toggle($post->id);
        return response()->json(['message' => 'Bookmark toggled']);
    }

    public function index()
    {
        $posts = auth()->user()->bookmarks()->with('category', 'user')->paginate(10);
        return response()->json($posts);
    }
}
