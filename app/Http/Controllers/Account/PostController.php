<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', auth()->user()->id)->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:30',
            'content' => 'required|string|max:5000',
            'thumbnail' => 'file'
        ]);

        $filePath = $request->thumbnail->store('images', 'public');
        $publicFilePath = asset("/storage/" . $filePath);

        Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'content' => $request->content,
            'thumbnail_url' => $publicFilePath
        ]);

        return response()->json([
            'status' => true
        ]);
    }

    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
