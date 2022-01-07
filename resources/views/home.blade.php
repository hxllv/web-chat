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
                    <form action="{{ route('home-post') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="What's on your mind" aria-label="Recipient's username" aria-describedby="basic-addon2" name="caption">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Post</button>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="image">Add photo</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/png, image/jpeg, image/webp">
                        </div>
                    </form>
                    @foreach ($posts as $post)
                        <div class ="row pt-4">
                            <div class="font-weight-bold d-flex w-100">
                                <a href="/chat-profile/{{ $post->user->id }}">
                                    <span class ="text-dark">{{ $post->user->username }}</span>
                                </a>
                                <div class="text-muted pl-1">at: {{ $post->created_at }}</div>
                            </div>
                            <div class="col-10 pt-2 pb-2">{{ $post->caption }}  </div>
                            <img src="/storage/{{  $post->image }}" class= "col-12" onerror="this.style.display='none';">                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
