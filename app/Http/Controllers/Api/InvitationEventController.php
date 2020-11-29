<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\InvitationEvent;
use App\Services\FriendshipService;
use Illuminate\Http\Request;

class InvitationEventController extends Controller
{
    /**
     * The InvitationEvent instance
     *
     * @var InvitationEvent
     */
    protected $invitation;

    /**
     * Create a new controller instance
     *
     * @param InvitationEvent $invitation
     * @return void
     */
    public function __construct(InvitationEvent $invitation)
    {
        $this->invitation = $invitation;

        $this->middleware('auth:api');
    }

    public function invitationAllFriends(int $id)
    {
        $user = auth()->user();
        $event = $user->events()->findOrFail($id);

        $friendService = app(FriendshipService::class);

        $friends = $friendService->myFriends($user->id);

        foreach ($friends as $friend) {
            if ($event->invitations()->where('user_id', $friend->id)->count() <= 0) {
                $event->invitations()->create([
                    'user_id' => $friend->id
                ]);
            }   
        }

        return response()->json([
            'error' => false,
            'message' => 'Convites enviados com sucesso',
            'data' => []
        ]);

    }
}
