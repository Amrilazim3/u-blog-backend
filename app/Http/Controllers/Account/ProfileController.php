<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . request()->user()->id
            ],
        ]);

        if ($request->hasFile('newProfileImage')) {
            if (
                $request->originalProfileImage &&
                Str::contains(
                    $request->originalProfileImage,
                    'u-blog-backend'
                )
            ) {
                $removeFilePath = Str::replace(asset('/storage/'), '', $request->originalProfileImage);
                Storage::disk('public')->delete($removeFilePath);
            }

            $filePath = $request->newProfileImage->store('images', 'public');
            $publicFilePath = asset("/storage/" . $filePath);
        }

        if ($request->isRemoveOriginalProfileImage == "true") {
            if (
                $request->originalProfileImage &&
                Str::contains(
                    $request->originalProfileImage,
                    'u-blog-backend'
                )
            ) {
                $removeFilePath = Str::replace(asset('/storage/'), '', $request->originalProfileImage);
                Storage::disk('public')->delete($removeFilePath);
            }
        }

        $user = User::find(request()->user()->id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'profile_image_url' => $request->hasFile('newProfileImage') ?
                $publicFilePath :
                ($request->isRemoveOriginalProfileImage == 'true' ? null : $request->originalProfileImage)
        ]);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
