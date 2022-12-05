<?php

use App\Http\Controllers\Account\PostController;
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

    Route::prefix('/user')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });

        Route::resource('posts', PostController::class)->except(['ceate', 'edit']);
    });
});
