<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', auth()->user()->id)
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
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
            'success' => true
        ]);
    }

    public function show($id)
    {
        $post = Post::where('id', $id)
            ->withCount(['comments', 'likes']
            )->first();

        return response()->json([
            'post' => $post
        ]);
    }

    public function update(Request $request, $id)
    {
        $defaultPost = Post::find($id);

        $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:5000',
        ]);

        if ($request->hasFile('thumbnail')) {
            $filePath = $request->thumbnail->store('images', 'public');
            $publicFilePath = asset("/storage/" . $filePath);

            $removeFilePath = Str::replace(asset('/storage/'), '', $defaultPost->thumbnail_url);
            Storage::disk('public')->delete($removeFilePath);
        }

        $defaultPost->update([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail_url' => $request->file('thumbnail') ? $publicFilePath : $defaultPost->thumbnail_url
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        $post->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
