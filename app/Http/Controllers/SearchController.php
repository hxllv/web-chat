<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('username', 'LIKE', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->get();

        $friendRequestsReceived = auth()->user()->friendsOf()->wherePivot('accepted', '=', 0)->get();
        $friendRequestsSent = auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 0)->get();
        $friendRequests = $friendRequestsReceived->merge($friendRequestsSent);

        $friendsOfMine = auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 1)->get();
        $friendsOf = auth()->user()->friendsOf()->wherePivot('accepted', '=', 1)->get();
        $friends = $friendsOfMine->merge($friendsOf);

        if($users->isNotEmpty()) {
            foreach ($users as $user) {
                foreach ($friends as $friend) {
                    $user['isFriends'] = ($friend->id == $user->id);
                }
                if (!($user->isFriends)) {
                    foreach ($friendRequests as $friendRequest) {

                        $user['isRequested'] = ($friendRequest->id == $user->id);

                    }
                }
            }
            return view('search')->with('users', $users);
        }
        return view('search')->with('users');
    }
}
