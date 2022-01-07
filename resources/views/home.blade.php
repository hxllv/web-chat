@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome {{ $user->username }} !</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('You are logged in!') }}
                    <form action="/home" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="What's on your mind" aria-label="Recipient's username" aria-describedby="basic-addon2" name="caption">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Post</button>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="image">Add photo</label>
                            <input type="file" class="form-control-file" id="image", name="image" >
                        </div>
                    </form>
                        @foreach ($posts as $post)
                            <div class ="row pt-4">
                                <span class="font-weight-bold">
                                    <a href="/chat-profile/{{ $post->user->id }}">
                                        <span class ="text-dark">{{ $post->user->username }}: </span>
                                    </a>
                                </span>
                                <div class="col-12">Date and hour: {{ $post->created_at }}  </div>
                                <div class="col-10">{{ $post->caption }}  </div>
                                <img src="/storage/{{  $post->image }}" class= "col-12" onerror="this.style.display='none';">                            </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
