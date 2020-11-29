<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FriendshipService
{
    /**
     * Take friends
     *
     * @param integer $id
     * @return Collection
     */
    public function myFriends(int $id): Collection
    {
        $friendships = DB::table('friendships')
            ->orWhere([
                'user_from' => $id,
                'user_to' => $id
            ])
            ->select('id', 'user_from', 'user_to')
            ->get()
            ->toArray();

        $users = [];

        foreach ($friendships as $key => $friendship) {
            if ($friendship->user_from === $id) {
                unset($friendships[$key]->user_from);
            }

            if ($friendship->user_to === $id) {
                unset($friendships[$key]->user_to);
            }

            $users[] = $friendship->user_from ?? $friendship->user_to;
        }

        $friends = DB::table('users')
            ->whereIn('id', $users)
            ->get();


        return $friends;
    }
}
