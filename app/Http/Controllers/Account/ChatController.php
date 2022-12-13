<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Engagement;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = Engagement::select(['id', 'user_id', 'engaged_id'])
            ->where('user_id', request()->user()->id)
            ->with(['following' => function ($query) {
                return $query->select(['id', 'name', 'profile_image_url']);
            }])
            ->get();

        $userChatIds = Engagement::where('user_id', request()->user()->id)->pluck('engaged_id');

        $chats = [];

        foreach ($userChatIds as $ids) {
            $res = Chat::select(['id', 'user_id_to', 'user_id_from', 'message'])
                ->where(function ($query) use ($ids) {
                    $query->where('user_id_to', $ids)
                        ->where('user_id_from', request()->user()->id);
                })
                ->orWhere(function ($query) use ($ids) {
                    $query->where('user_id_from', $ids)
                        ->where('user_id_to', request()->user()->id);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            $chats[] = $res;
        }

        return response()->json([
            'users' => $users,
            'chats' => $chats
        ]);
    }

    public function show($id)
    {
        $user = User::select(['id', 'name', 'profile_image_url'])->where('id',  $id)->first();

        $chats = Chat::select(['id', 'message', 'user_id_to'])->where(function ($query) use ($id) {
            $query->where('user_id_to', $id)
                ->where('user_id_from', request()->user()->id);
        })->orWhere(function ($query) use ($id) {
            $query->where('user_id_from', $id)
                ->where('user_id_to', request()->user()->id);
        })->get();

        return response()->json([
            'user' => $user,
            'chats' => $chats
        ]);
    }

    public function store(Request $request)
    {
        $chat = Chat::create([
            'user_id_from' => request()->user()->id,
            'user_id_to' => $request->to,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'chat' => $chat
        ]);
    }

    public function destroy($id)
    {
        Chat::find($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
