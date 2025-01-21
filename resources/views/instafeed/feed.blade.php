@extends('layouts.app')

@section('content')

@foreach ($posts as $post)
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center" style="gap:10px;">
            <img src="{{ $post->user->image }}" class="rounded-circle me-5" style="width: 50px; height:50px;" alt="User">
            <i>
              <strong>
                <a href="{{ Auth::id() === $post->user->id ? route('home') : route('user.profile', $post->user->id) }}" 
                   style="text-decoration: none; color: inherit;">
                  {{ $post->user->name }}
                </a>
              </strong>
            </i>
          </div>
          <span style="font-weight: 400; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">{{ $post->created_at->diffForHumans() }}</span>
        </div>
        <img src="{{ $post->image }}" class="card-img-top {{ $post->filter }}" alt="Post Image">

        <div class="card-body">
          <div class="d-flex justify-content-start align-items-center mb-2" style="margin-top: -10px;"> 
            <a href="{{ route('post.likes', $post->id) }}" 
               style="color: inherit; font-size: 14px; font-weight: 500; color: #007bff;">
              View Likes
            </a>
          </div>

          <div class="d-flex justify-content-start align-items-center mb-4">
            <a href="{{ route('post.like', $post->id) }}" 
               class="like-link" 
               data-id="{{ $post->id }}" 
               style="text-decoration: none; color: inherit;">
              <i class="fa-heart fa-lg like-icon {{ $post->likes->contains('liker_id', Auth::id()) ? 'fa-solid text-danger' : 'fa-regular' }}" 
                 style="cursor: pointer; font-size:22px; transition: color 0.3s; margin-right: 5px;">
              </i>
            </a>
            <span id="like-count-{{ $post->id }}" class="like-comment-count mb-1" style="margin-right: 15px;">{{ $post->likes->count() }}</span>
            <a href="{{ route('post.comments', $post->id) }}" style="text-decoration: none; color: inherit;">
              <i class="fa-regular fa-comment fa-lg" style="cursor: pointer;font-size:22px; margin-right: 5px;"></i>
            </a>
            <span class="like-comment-count mb-1">{{ $post->comments->count() }}</span>
          </div>

          @if($post->caption != null)
          <p style="margin-top:-10px;">
            <strong>
              <a href="{{ Auth::id() === $post->user->id ? route('home') : route('user.profile', $post->user->id) }}" 
                 style="text-decoration: none; color: inherit;">
                {{ $post->user->name }}
              </a>
            </strong> 
            {{ $post->caption }}
          </p>
          @endif
          <hr>
          <div>
            @foreach($post->comments->take(3) as $comment)
            <p class="mb-1">
              <strong>
                <a href="{{ Auth::id() === $comment->user->id ? route('home') : route('user.profile', $comment->user->id) }}" 
                   style="text-decoration: none; color: inherit;">
                  {{ $comment->user->name }}
                </a>
              </strong> 
              {{ $comment->content }}
            </p>
            @endforeach
            <a href="{{ route('posts.show', $post->id) }}" class="text-muted" style="font-size: 14px; margin-top:15px;">View Post</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection

