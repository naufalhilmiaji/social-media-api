<?php

use App\Http\Controllers\API\FriendRequestController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user', [UserController::class, 'index'])->name('user_lists');
Route::get('user/show/{id}', [UserController::class, 'show'])->name('user_show');
Route::post('user/store', [UserController::class, 'store'])->name('user_store');
Route::post('user/update/{id}', [UserController::class, 'update'])->name('user_update');
Route::delete('user/destroy/{id}', [UserController::class, 'destroy'])->name('user_destroy');

Route::post('user/request', [FriendRequestController::class, 'askFriendRequest'])->name('request');
Route::post('user/request/lists', [FriendRequestController::class, 'showAllRequests'])->name('show_requests');
Route::post('user/request/{id}/accept', [FriendRequestController::class, 'acceptFriendRequest'])->name('accept_request');
Route::post('user/request/{id}/reject', [FriendRequestController::class, 'rejectFriendRequest'])->name('reject_request');

Route::post('user/friends', [FriendRequestController::class, 'showAllFriends'])->name('show_friends');
Route::post('user/friends/mutuals', [FriendRequestController::class, 'showCommonFriends'])->name('mutual_friends');

Route::post('user/block', [FriendRequestController::class, 'blockUser'])->name('block_user');
