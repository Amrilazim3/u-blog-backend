<?php

use App\Http\Controllers\Account\ChatController;
use App\Http\Controllers\Account\CommentController;
use App\Http\Controllers\Account\EngagementController;
use App\Http\Controllers\Account\LikeController;
use App\Http\Controllers\PostController as ExplorePostController;
use App\Http\Controllers\Account\PostController as AccountPostController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\SearchChatController;
use App\Http\Controllers\User\PostController as UserPostController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SearchController;
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
    Route::get("/posts/featured", [ExplorePostController::class, 'featured']);
    Route::get("/posts/latest", [ExplorePostController::class, 'latest']);
    Route::get("/posts/trending", [ExplorePostController::class, 'trending']);

    Route::get('/explore/search', [SearchController::class, '__invoke']);

    Route::get("/users/{user}/posts", [UserPostController::class, 'index']);
    Route::get("/users/{user}/posts/{post}", [UserPostController::class, 'show']);
    Route::post("/users/{user}/follow", [EngagementController::class, 'store']);
    Route::delete("/users/{user}/follow", [EngagementController::class, 'destroy']);

    Route::prefix('/account')->group(function () {
        Route::patch('/profile', [ProfileController::class, 'update']);

        Route::resource('posts', AccountPostController::class)->except(['ceate', 'edit']);

        Route::get('chats/search', [SearchChatController::class, '__invoke']);
        Route::get('chats', [ChatController::class, 'index']);
        Route::get('chats/{user}', [ChatController::class, 'show']);
        Route::post('chats', [ChatController::class, 'store']);
        Route::delete('chats/{chat}', [ChatController::class, 'destroy']);
    });

    Route::get('/posts/{post}/likes', [LikeController::class, 'index']);
    Route::post('/posts/{post}/likes', [LikeController::class, 'store']);
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy']);
    
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);
});
