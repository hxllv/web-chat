@extends('layouts.app')

@section('krneki')

<style>
    .border.col-6 > :nth-child(odd) {
        background-color: #dcdcdc;
    }
    .border.col-6 > :nth-child(even) {
        background-color: #ececec;
    }
    .border.col-6 > h1 {
        background-color: transparent !important;
    }
</style>

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="border col-6">
            <h1>Friends</h1>
        @foreach ($friends as $friend)
            <div class="row p-2 d-flex align-items-baseline">
                <a class="col" href="{{ route('chat-profile-not-me', $friend->id) }}">
                    {{ $friend->username }}
                </a>
                <div class="col">
                    <a class="btn btn-primary" href=" {{ route('chat', $friend->id) }} ">Have a chat</a>

                </div>
                <form class="col pl-2" action="{{ route('deny-request', [$friend->id, 'friend']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input class="btn btn-danger" type="submit" value="Unfriend">
                </form>
            </div>
        @endforeach
        </div>
        <div class="border col-6">
            <h1>Friend requests</h1>
        @foreach ($friendRequests as $friend)
            <div class="row p-2 d-flex align-items-baseline" >
                <a class="col" href="{{ route('chat-profile-not-me', $friend->id) }}">
                    {{ $friend->username }}
                </a>
                <form class="pl-2 col" action="{{ route('accept-request', [$friend->id, 'friend']) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input class="btn btn-success" type="submit" value="Accept request">
                </form>
                <form class="pl-2 col" action="{{ route('deny-request', [$friend->id, 'friend']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input class="btn btn-danger" type="submit" value="Deny request">
                </form>
            </div>
        @endforeach
        </div>
    </div>
</div>

@endsection