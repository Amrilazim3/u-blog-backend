<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke()
    {
        $users = User::search(request(['search']))
            ->select('id', 'name', 'profile_image_url')
            ->paginate(5);

        $posts = Post::search(request(['search']))
            ->select('id', 'title', 'user_id')
            ->paginate(5);

        return response()->json([
            'users' => $users,
            'posts' => $posts
        ]);
    }
}
