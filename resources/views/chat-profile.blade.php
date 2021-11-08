@extends('layouts.app')

@section('content')

<div class="container">
    <div>
        <h1>{{ $user['username'] }}</h1>
        @if(auth()->user()->id !== $user->id && !$isRequestedForMe)
        <friend-button user-id="{{ $user->id }}" is-requested="{{ $isRequested }}" is-friends="{{ $isFriends }}"></friend-button>
        @elseif ($isRequestedForMe)
        <form action="{{ route('accept-request', [$user, 'chat-profile']) }}" method="POST">
            @csrf
            @method('PATCH')
            <input class="btn btn-success" type="submit" value="Accept request">
        </form>
        <form action="{{ route('deny-request', [$user, 'chat-profile']) }}" method="POST">
            @csrf
            @method('DELETE')
            <input class="btn btn-danger" type="submit" value="Deny request">
        </form>
        @endif  
    </div>
</div>

@endsection