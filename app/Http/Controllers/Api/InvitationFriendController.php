<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\InvitationFriend;
use App\Models\User;
use Illuminate\Http\Request;

class InvitationFriendController extends Controller
{
    /**
     * The InvitationFriend instance
     * 
     * @var InvitationFriend
     */
    protected $invitation;

    /**
     * The User Instance
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new controller instance
     *
     * @param InvitationFriend $invitation
     * @param User $user
     * @return void
     */
    public function __construct(InvitationFriend $invitation, User $user)
    {
        $this->invitation = $invitation;
        $this->user = $user;

        $this->middleware('auth:api');
    }

    /**
     * Create a new friend invitation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerInvitation(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $user = auth()->user();

        if (!$receiver = $this->user->where('email', $data['email'])->first() ?? false) {

            // Envia email para $data['email']

            return response()->json([
                'error' => false,
                'message' => 'Enviamos um convite para o seu amigo criar um conta.',
                'data' => []
            ]);
        }

        if ($user->invitations()->where('email_receiver', $receiver->email)->count() > 0) {
            return response()->json([
                'error' => false,
                'message' => 'Você já enviou um convite para este amigo.',
                'data' => []
            ]);
        }

        $user->invitations()->create([
            'email_receiver' => $receiver->email
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Convite criado com sucesso! Só aguardar a resposta.',
            'data' => []
        ]);
    }

    public function actionInvitation(Request $request, int $id)
    {
        $data = $request->validate(['action' => 'required|string|in:rejected,approved']);

        $invitation = $this->invitation->findOrFail($id);

        $invitation->update(['status' => $data['action']]);

        if ($invitation->status === 'approved') {
            Friendship::create([
                'user_from' => $invitation->sender->id,
                'user_to' => $invitation->receiver->id
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Pedido de amizade aceito com sucesso.',
                'data' => []
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Pedido de amizade rejeitado.',
            'data' => []
        ]);
    }

    /**
     * Get authenticated user invitations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myInvitations()
    {
        return response()->json([
            'error' => false,
            'message' => '',
            'data' => auth()->user()->invitations
        ]);
    }

    /**
     * Take authenticated user friend requests
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myFriendRequest()
    {
        return response()->json([
            'error' => false,
            'message' => '',
            'data' => auth()->user()->friendRequests
        ]);
    }
}
