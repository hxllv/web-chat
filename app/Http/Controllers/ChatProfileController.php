<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $curUser = auth()->user();
        return view('chat-profile')->with('user', $curUser)->with('isRequestedForMe', false);
    }

    public function show(\App\Models\User $user)
    {
        $isRequested = count(auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 0)->wherePivot('friend_id', '=', $user->id)->get()) ? true : false;
        $isRequestedForMe = count(auth()->user()->friendsOf()->wherePivot('accepted', '=', 0)->wherePivot('user_id', '=', $user->id)->get()) ? true : false;
        $isFriendOfMine = count(auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 1)->wherePivot('friend_id', '=', $user->id)->get()) ? true : false;
        $isFriendOf = count(auth()->user()->friendsOf()->wherePivot('accepted', '=', 1)->wherePivot('user_id', '=', $user->id)->get()) ? true : false;
        $isFriends = $isFriendOfMine || $isFriendOf;
        return view('chat-profile')->with('user', $user)->with('isRequested', $isRequested)->with('isRequestedForMe', $isRequestedForMe)->with('isFriends', $isFriends);
    }

}
