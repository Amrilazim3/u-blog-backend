<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Engagement;
use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function store($id)
    {
        Engagement::create([
            'user_id' => request()->user()->id,
            'engaged_id' => $id
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        Engagement::where('user_id', request()->user()->id)
            ->where('engaged_id', $id)
            ->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
