<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($id)
    {
        $post = Post::with(['comments' => function ($query) {
            $query->with(['user' => function ($query) {
                $query->select(['id', 'name']);
            }])->orderBy('created_at', 'desc');
        }])->find($id);

        return response()->json([
            'comments' => $post->comments
        ]);
    }

    public function store(Request $request, $id)
    {
        Comment::create([
            'user_id' => request()->user()->id,
            'post_id' => $id,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($postId, $commentId)
    {
        Comment::find($commentId)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
