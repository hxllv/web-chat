<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'auth']);
    }

    public function show(\App\Models\User $user)
    {
        /* $friendOfMine = auth()->user()->friendsOfMine()->wherePivot('friend_id', '=', $user->id)->get();
        $friendOf = auth()->user()->friendsOf()->wherePivot('user_id', '=', $user->id)->get();

        dd(!empty($friendOfMine) || !empty($friendOf)); */

        if (auth()->user()->cannot('show', $user)) abort(403);

        $messagesSent = auth()->user()->messagesSent()->where('receiver_id', '=', $user->id)->get();
        $messagesReceived = auth()->user()->messagesReceived()->where('sender_id', '=', $user->id)->get();

        $messages = $messagesSent->concat($messagesReceived);

        return isset($_GET['not-view']) &&
            !empty($_GET['not-view']) &&
            $_GET['not-view'] == 'true' ?
            $messages->sortBy('created_at')->values()->toJson() :
            view('chat')->with('messages', $messages->sortBy('created_at')->values())->with('user', $user);
    }

    public function store(\App\Models\User $user)
    {
        //dd(request("media"));

        $data = request()->validate([
            'message' => 'nullable|string',
            'media' => 'nullable|file',
            'audio' => 'nullable|file',
            'messageType' => 'required|numeric'
        ]);

        $message = null;
        $media = null;
        $audio = null;

        if (isset($data['message']) && !empty($data['message']) && $data['messageType'] == 0) {
            $message = $data['message'];
        } else if (isset($data['media']) && !empty($data['media']) && $data['messageType'] == 2) {
            $media = $data['media']->store('uploads', 'public');
        } else if (isset($data['audio']) && !empty($data['audio']) && $data['messageType'] == 1) {
            $audio = $data['audio']->store('uploads', 'public');
        }

        if ($message === null && $media === null && $audio === null)
            return response()->json(['message' => 'Message was null.'], 500);

        return auth()->user()->messagesSent()->create([
            'receiver_id' => $user->id,
            'message' => $message,
            'media' => $media,
            'audio' => $audio,
            'message_type' => $data['messageType']
        ]);
    }
}
