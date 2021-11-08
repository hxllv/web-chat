@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-6">
        @foreach ($users as $user)
            <div class="row p-2 d-flex align-items-baseline">
                <a class="col" href="{{ route('chat-profile-not-me', $friend->id) }}">
                    {{ $user->username }}
                </a>

                <friend-button user-id="{{ $user->id }}" is-requested="{{ $isRequested }}" is-friends="{{ $isFriends }}"></friend-button>
            </div>

        @endforeach
    </div>
</div>

@endsection