@extends('layouts.app')

@section('content')
<div id="app">
    <header>
        <div class="container" style="margin-bottom: 100px;">
            <div class="profile">
                <div class="profile-image mr-5">
                    <img src="{{asset($user->image)}}" class="rounded-circle" width="100%" height="290px" alt="">
                </div>

                <div class="row">
                <div class="profile-user-settings">
                    <h1 class="profile-user-name" style="font-size:28px; font-weight:500;" >{{$user->name}}</h1>
                </div>
                <div class="messagefollowbuttons" style="padding-left:30px;">
                    <button type="button" id="follow-button" class="btn btn-primary mr-2 mx-4 mb-3" style="width: 100px; padding: 8px 20px; font-size:14px;" data-id="{{ $user->id }}">
                Follow
                    </button>

                    <a href="{{route('chat.form', $user->id)}}"><button type="button" class="btn btn-secondary mb-3" style="width: 100px; padding: 8px 20px;font-size:14px; ">Message</button></a>
                </div>
                </div>

                <div class="profile-stats" style="margin: 30px 0px;">
                    <ul>
                        <li><span class="profile-stat-count">{{$postcount}}</span> Posts</li>
                        <li><span class="profile-stat-count">{{$followcount}}</span> Followers</li>
                        <li><span class="profile-stat-count">{{$isfollowingcount}}</span> Following</li>
                    </ul>
                </div>

                <div class="profile-bio">
                    <p><span class="profile-real-name">{{$user->bio}}</p>
                </div>
            </div>
        </div>
    </header>

    <main>
    <div class="container">
    
    <div class="gallery">
        <div class="row ">





        

       @foreach ($posts as $post )
       
       
    <div class="gallery-item col-md-4" tabindex="0">
         <a href="{{route('posts.show', $post->id)}}">
            <div class="outer">

                <img src="{{asset($post->image)}}" class="gallery-image img-fluid {{$post->filter}} pt-3 pb-3" alt="" >
                <div class="inner" style="height:275px; margin-top:12px;">
                    <ul>
                        <li class=""><i class="fa fa-heart" aria-hidden="true"></i> {{ $post->likes->count() }}</li>
                        <li class=""><i class="fa fa-comment" aria-hidden="true"></i> {{$post->comments->count()}}</li>
                    </ul>
                </div>
            </div>

        </a>


    </div>

    @endforeach
    </div>
 </div>
</div>
</main>
</div>

@endsection