<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'auth']);
    }

    public function friendsApi()
    {
        $friendsOfMine = auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 1)->get();
        $friendsOf = auth()->user()->friendsOf()->wherePivot('accepted', '=', 1)->get();

        //$friendRequests = auth()->user()->friendsOf()->wherePivot('accepted', '=', 0)->get();

        $friends = $friendsOfMine->merge($friendsOf);

        return response()->json($friends);
        //return view('friends')->with('friends', $friends)->with('friendRequests', $friendRequests);
    }

    public function requestsApi()
    {
        /* $friendsOfMine = auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 1)->get();
        $friendsOf = auth()->user()->friendsOf()->wherePivot('accepted', '=', 1)->get(); */

        $friendRequests = auth()->user()->friendsOf()->wherePivot('accepted', '=', 0)->get();

        /* $friends = $friendsOfMine->merge($friendsOf); */

        return response()->json($friendRequests);
        //return view('friends')->with('friends', $friends)->with('friendRequests', $friendRequests);
    }

    public function index()
    {
        $friendsOfMine = auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 1)->get();
        $friendsOf = auth()->user()->friendsOf()->wherePivot('accepted', '=', 1)->get();

        $friendRequests = auth()->user()->friendsOf()->wherePivot('accepted', '=', 0)->get();

        $friends = $friendsOfMine->merge($friendsOf);

        return view('friends')->with('friends', $friends)->with('friendRequests', $friendRequests);
    }

    public function store($user)
    {
        return auth()->user()->friendsOfMine()->toggle($user);
    }

    public function delete($user, $page)
    {
        $page == 'friend' ? auth()->user()->friendsOfMine()->detach($user) : '';
        auth()->user()->friendsOf()->detach($user);

        return redirect('/' . $page . '/' . ($page == 'chat-profile' ? $user : ''));
    }

    public function delete2($user)
    {
        auth()->user()->friendsOfMine()->detach($user);
        auth()->user()->friendsOf()->detach($user);

        return 0;
    }

    public function update($user, $page)
    {
        auth()->user()->friendsOf()->wherePivot('user_id', '=', $user)->update(['accepted' => 1]);

        return redirect('/' . $page . '/' . ($page == 'chat-profile' ? $user : ''));
    }

    public function update2($user, $page)
    {
        auth()->user()->friendsOf()->wherePivot('user_id', '=', $user)->update(['accepted' => 1]);

        return 0;
    }
}
