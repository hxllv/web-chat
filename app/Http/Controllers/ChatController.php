<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(\App\Models\User $user) {
        /* $friendOfMine = auth()->user()->friendsOfMine()->wherePivot('friend_id', '=', $user->id)->get();
        $friendOf = auth()->user()->friendsOf()->wherePivot('user_id', '=', $user->id)->get();

        dd(!empty($friendOfMine) || !empty($friendOf)); */

        if (auth()->user()->cannot('show', $user)) abort(403);

        $messagesSent = auth()->user()->messagesSent()->where('receiver_id', '=', $user->id)->get();
        $messagesReceived = auth()->user()->messagesReceived()->where('sender_id', '=', $user->id)->get();

        $messages = $messagesSent->concat($messagesReceived);
    
        return isset($_GET['not-view']) && !empty($_GET['not-view']) && $_GET['not-view'] == 'true' ? $messages->sortBy('created_at')->values()->toJson() : view('chat')->with('messages', $messages->sortBy('created_at')->values())->with('user', $user);
    }

    public function store(\App\Models\User $user) {
        $data = request()->validate([
            'message' => 'required|string'
        ]);

        return auth()->user()->messagesSent()->create(['receiver_id' => $user->id, 'message' => $data['message']]);
    }
}
