@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ $user->username }}</h1>
        <chat-box messages="{{ $messages->toJson() }}" 
            id-sender="{{ auth()->user()->id }}" id-receiver="{{ $user->id }}">
        </chat-box>
    </div>

@endsection