<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user' => function ($user) {
            return $user->select(['id', 'name']);
        }])
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function featured()
    {
        $posts = Post::with(['user' => function ($user) {
            return $user->select(['id', 'name']);
        }])
            ->withCount('likes')
            ->inRandomOrder()
            ->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function latest()
    {
        $posts = Post::with(['user' => function ($user) {
            return $user->select(['id', 'name']);
        }])
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function trending()
    {
        $posts = Post::with(['user' => function ($user) {
            return $user->select(['id', 'name']);
        }])
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }
}
