<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(User $user)
    {
        $posts = Post::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'posts' => $posts,
            'user' => $user
        ]);
    }

    public function show(User $user, Post $post)
    {
        $rPost = Post::with('user')
            ->withCount(['comments', 'likes'])
            ->find($post->id);

        return response()->json([
            'post' => $rPost
        ]);
    }
}
