<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Engagement;
use App\Models\User;
use Illuminate\Http\Request;

class SearchChatController extends Controller
{
    public function __invoke()
    {
        $userIds = User::where('name', 'like', '%' . request('search') . '%')
            ->pluck('id');

        $users = Engagement::select(['id', 'user_id', 'engaged_id'])
            ->where('user_id', request()->user()->id)
            ->whereIn('engaged_id', $userIds)
            ->with(['following' => function ($query) {
                return $query->select(['id', 'name', 'profile_image_url']);
            }])
            ->get();

        $userChatIds = Engagement::where('user_id', request()->user()->id)
            ->whereIn('engaged_id', $userIds)
            ->pluck('engaged_id');

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
}
