<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like($id)
    {
        Like::create([
            'user_id' => request()->user()->id,
            'post_id' => $id
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function unlike($id)
    {
        Like::where('user_id', request()->user()->id)
            ->where('post_id', $id)
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function hasLikePost($id)
    {
        $isExists = Like::where('user_id', request()->user()->id)
            ->where('post_id', $id)
            ->exists();

        if ($isExists) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }
}
