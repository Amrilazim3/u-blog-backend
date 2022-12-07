<?php

use App\Http\Controllers\Account\LikeController;
use App\Http\Controllers\PostController as ExplorePostController;
use App\Http\Controllers\Account\PostController as AccountPostController;
use App\Http\Controllers\User\PostController as UserPostController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/logout", [LoginController::class, 'logout']);

    Route::get("/posts", [ExplorePostController::class, 'index']);

    Route::get("/users/{user}/posts", [UserPostController::class, 'index']);
    Route::get("/users/{user}/posts/{post}", [UserPostController::class, 'show']);

    Route::prefix('/account')->group(function () {
        Route::resource('posts', AccountPostController::class)->except(['ceate', 'edit']);
    });

    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlike']);
    Route::get('/posts/{post}/has-like-post', [LikeController::class, 'hasLikePost']);
});
