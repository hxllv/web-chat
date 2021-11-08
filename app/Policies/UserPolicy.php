<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function show(User $user, User $friend)
    {
        $friendOfMine = $user->friendsOfMine()->wherePivot('friend_id', '=', $friend->id)->get();
        $friendOf = $user->friendsOf()->wherePivot('user_id', '=', $friend->id)->get();

        return !$friendOfMine->isEmpty() || !$friendOf->isEmpty();
    }
}
