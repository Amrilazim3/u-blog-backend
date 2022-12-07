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
        }])->orderBy('created_at', 'desc')->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }
}
