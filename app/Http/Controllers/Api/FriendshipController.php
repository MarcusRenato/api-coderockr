<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use App\Services\FriendshipService;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    /**
     * The Friendship instance
     * 
     * @var Friendship
     */
    protected $friendship;

    /**
     * The User Instance
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new controller instance
     *
     * @param Friendship $friendship
     * @return void
     */
    public function __construct(Friendship $friendship, User $user)
    {
        $this->friendship = $friendship;
        $this->user = $user;

        $this->middleware('auth:api');
    }

    public function myFriends()
    {
        $service = app(FriendshipService::class);

        return response()->json([
            'error' => false,
            'message' => '',
            'data' => UserResource::collection($service->myFriends(auth()->id()))
        ]);
    }

    public function undoFriendship(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|string']);

        $friend = $this->user->where('email', $data['email'])->first() ?? false;

        if (!$friend) {
            return response()->json([
                'error' => 'true',
                'message' => 'usuário não encontrado',
                'data' => []
            ]);
        }

        $friendship = $this->friendship
            ->orWhere([
                'user_from' => $friend->id,
                'user_to' => $friend->id
            ])
            ->orWhere([
                'user_from' => auth()->id(),
                'user_to' => auth()->id()
            ])
            ->first();

        if ($friendship->delete()) {
            return response()->json([
                'error' => false,
                'message' => 'Excluído com sucesso',
                'data' => []
            ], 200);
        }
    }
}
