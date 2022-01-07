<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $friendsid = auth()->user()->friendsOfMine()->wherePivot('accepted', '=', 1)->pluck('friend_id')->toArray();
        $friends = auth()->user()->friendsOf()->wherePivot('accepted', '=', 1)->pluck('user_id')->toArray();
        $friendsid = array_merge($friendsid, $friends); 
        //add my id
        array_push($friendsid, auth()->user()->id);
        $posts = Post::whereIn('user_id', $friendsid)->latest()->get();
        $user = auth()->user();
        return view('/home')->with('posts', $posts)->with('user', $user);
    }

    public function store()
    {
        $data = request()->validate([
            'caption' => 'nullable|string',
            'image' => 'required_if:caption,null|file'
        ]);

        $caption = null;
        $image = null;

        if (isset($data['caption']) && !empty($data['caption'])) {
            $caption = $data['caption'];
        }
        if (isset($data['image']) && !empty($data['image'])) {
            $image = $data['image']->store('uploads', 'public');
        }

        auth()->user()->posts()->create([
            'caption' => $caption,
            'image' => $image
        ]);

        return redirect('/home');
    }
}
