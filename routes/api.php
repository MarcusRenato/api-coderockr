<?php

use App\Http\Controllers\Api\{
    AuthController,
    EventController,
    FriendshipController,
    InvitationEventController,
    InvitationFriendController,
    UserController
};
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// user
Route::post('user', [UserController::class, 'store']);

// auth
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

// events
Route::post('event/store', [EventController::class, 'store']);
Route::put('event/update/{id}', [EventController::class, 'update']);
Route::delete('event/destroy/{id}', [EventController::class, 'destroy']);
Route::get('event/{id}', [EventController::class, 'show'])->name('event.single');

Route::get('events', [EventController::class, 'index']);

// friend invitations
Route::post('invitation', [InvitationFriendController::class, 'registerInvitation']);
Route::get('invitations/my', [InvitationFriendController::class, 'myInvitations']);
Route::get('friend-requests/my', [InvitationFriendController::class, 'myFriendRequest']);

Route::post('action-invitation/{id}', [InvitationFriendController::class, 'actionInvitation']);

Route::get('friends/my', [FriendshipController::class, 'myFriends']);
Route::get('friends/undo-friendship', [FriendshipController::class, 'undoFriendship']);

Route::post('event-invitations/all/{id}', [InvitationEventController::class, 'invitationAllFriends']);