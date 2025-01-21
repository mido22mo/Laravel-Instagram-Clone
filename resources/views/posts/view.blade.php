@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container w-75 mx-auto mt-5">
        <div class="row">
            <div class="col-md-8">
                <img src="{{ asset($posts->image) }}" class="img-fluid {{ $posts->filter }}" alt="Post Image">
            </div>
            <div class="col-md-4">
                <d class="card">
                    <div class="d-flex align-items-center p-3">
                        <img src="{{ asset($posts->user->image) }}" class="rounded-circle" width="50" alt="User Image">
                        <a href="{{ route('user.profile', $posts->user->id) }}" class="ml-3 text-decoration-none">
                            <h5 class="mb-0 text-dark fw-bolder">{{ $posts->user->name }}</h5>
                        </a>
                        @if($posts->user->id === Auth::id())
                        <div class="ml-auto">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editCaptionModal">Edit Caption</button>
                                <form action="{{ route('posts.destroy', $posts->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item" type="submit" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</button>
                                </form>
                            </div>
                        </div>
                        @endif

                    </div>
                        
                    <div class="modal fade" id="editCaptionModal" tabindex="-1" aria-labelledby="editCaptionModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Caption</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('posts.update', $posts->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label>Edit Caption</label>
                                            <input type="text" name="caption" class="form-control" value="{{ $posts->caption }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        <p class="mb-0">{{ $posts->caption }}</p>
                    </div>

                    <div class="d-flex align-items-center p-3 position-relative">
                        <a href="{{ route('post.like', $posts->id) }}" class="like-link" data-id="{{ $posts->id }}" style="text-decoration: none; color: inherit;">
                            <i class="fa-heart fa-lg like-icon {{ $posts->likes->contains('liker_id', Auth::id()) ? 'fa-solid text-danger' : 'fa-regular' }}" style="cursor: pointer; font-size:22px; transition: color 0.3s; margin-right: 5px;"></i>
                        </a>
                        <span class="ml-2 mb-1" id="like-count-{{ $posts->id }}" style="font-size:16px;">{{ $posts->likes->count() }}</span>
                        <div class="position-absolute" style="bottom: 0; right: 7px; font-size: 15px; color: #888;">
                            <small style="font-size:13px;">{{ $posts->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
    @foreach ($comments as $comment)
    <li class="list-group-item">
        <div class="d-flex align-items-start">
            <img src="{{ asset($comment->user->image) }}" class="rounded-circle mr-3" width="40" alt="User Image">
            <div class="flex-grow-1">
                <strong>{{ $comment->user->name }}</strong>
                <small class="text-muted d-block" style="font-size:12px;">{{ $comment->created_at->diffForHumans() }}</small>
                <p class="mb-0">{{ $comment->content }}</p>
            </div>

            <div class="ml-auto">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
                    @if($comment->user->id === Auth::id())
                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editCommentModal-{{ $comment->id }}">Edit Comment</button>
                    <form action="{{ route('comment.delete', $comment->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item" type="submit" onclick="return confirm('Are you sure you want to delete this comment?')">Delete Comment</button>
                    </form>
                    @endif

                    @if($posts->user->id === Auth::id() && $comment->user->id !== Auth::id())
                    <form action="{{ route('comment.delete', $comment->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item" type="submit" onclick="return confirm('Are you sure you want to delete this comment?')">Delete Comment</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        @if($comment->user->id === Auth::id() || $posts->user->id === Auth::id())
        <div class="modal fade" id="editCommentModal-{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('comment.update', $comment->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="form-group">
                                <label>Edit Comment</label>
                                <input type="text" name="content" class="form-control" value="{{ $comment->content }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </li>
    @endforeach
</ul>

                    <div class="p-3">
                        <form action="{{ route('comment.add', $posts->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="content" class="form-control" placeholder="Add a comment..." required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Comment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
