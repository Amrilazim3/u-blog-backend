<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Engagement;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(User $user)
    {
        $posts = Post::where('user_id', $user->id)
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $followers = $user->followers()->count();

        $following = $user->following()->count();

        $postsCount = $user->posts()->count();

        $isFollow = Engagement::where('user_id', request()->user()->id)
            ->where('engaged_id', $user->id)
            ->exists();

        return response()->json([
            'posts' => $posts,
            'user' => $user,
            'followers' => $followers,
            'following' => $following,
            'postsCount' => $postsCount,
            'isFollow' => $isFollow,
        ]);
    }

    public function show(User $user, Post $post)
    {
        $rPost = Post::with('user')
            ->withCount(['comments', 'likes'])
            ->find($post->id);

        $isFollow = Engagement::where('user_id', request()->user()->id)
            ->where('engaged_id', $user->id)
            ->exists();

        return response()->json([
            'post' => $rPost,
            'isFollow' => $isFollow
        ]);
    }
}
