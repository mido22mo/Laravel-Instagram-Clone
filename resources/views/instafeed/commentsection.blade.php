@extends('layouts.app')

@section('content')
<div class="container mt-3">
    @if ($comments->isEmpty())
        <div class="alert alert-info text-center" style="font-family: 'Arial', sans-serif; color: #34495e; font-size: 18px;">
            Nobody Commented On This Post
        </div>
    @else
    <ul class="list-group list-group-flush" style="max-height: auto; overflow-y: auto; padding: 0;">
        @foreach ($comments as $comment)
            <li class="list-group-item px-4 py-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <img src="{{ asset($comment->user->image) }}" class="rounded-circle me-3" width="50" height="50" alt="User Image">
                        <div>
                            <h6 class="mb-0" style="font-family: 'Arial', sans-serif; font-weight: bold; color: #2c3e50; font-size:15px;">
                                {{ $comment->user->name }}
                            </h6>
                        </div>
                    </div>
                    <small class="text-muted" style="font-size:13px;">{{ $comment->created_at->diffForHumans() }}</small>

                    @if (Auth::check() && Auth::user()->id === $comment->user_id)
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Options
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">Edit Comment</a>
                                </li>
                                <li>
                                    <form action="{{ route('comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">Delete Comment</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                <p class="mb-0 ps-5 text-dark pt-4" style="font-size: 13px;">{{ $comment->content }}</p>
            </li>

            <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('comment.update', $comment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="content" class="form-label">Comment Content</label>
                                    <textarea name="content" id="content" class="form-control" rows="4" required>{{ $comment->content }}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </ul>
    @endif

    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3" style="font-family: 'Arial', sans-serif; color: #34495e;">Leave a Comment</h5>
            <form action="{{ route('comment.add', $post->id) }}" method="post">
                @csrf
                <div class="input-group">
                    <input type="text" name="content" class="form-control" placeholder="Write a comment..." aria-label="Write a comment...">
                    <button type="submit" class="btn btn-primary px-4">Comment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection