@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-6 ">
        @if($users)
            @foreach ($users as $user)
                <div class="row p-2 d-flex align-items-baseline border-bottom">
                    <a class="col" href="{{ route('chat-profile-not-me', $user->id) }}">
                        {{ $user->username }}
                    </a>

                    <friend-button user-id="{{ $user->id }}" is-requested="{{ $user-> isRequested }}" is-friends="{{ $user -> isFriends }}"></friend-button>
                </div>

            @endforeach
        @else
            <div>No users found</div>
        @endif
    </div>

</div>

@endsection
