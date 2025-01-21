@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h5 class="mb-4" style="font-family: 'Arial', sans-serif; color: #34495e; font-weight: bold;">Users Who Liked This Post</h5>
    @if ($likes->isEmpty())
        <div class="alert alert-info text-center" style="font-family: 'Arial', sans-serif; color: #34495e; font-size: 18px;">
            Nobody Liked This Post
        </div>
    @else
        <ul class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto; padding: 0;">
            @foreach ($likes as $like)
                <li class="list-group-item px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="gap:10px;">
                            <img src="{{ asset($like->user->image) }}" class="rounded-circle me-3" width="50" height="50" alt="User Image">
                            <div>
                                <h6 class="mb-0" style="font-family: 'Arial', sans-serif; font-weight: bold; color: #2c3e50; font-size:15px;">
                                    <a href="{{ Auth::id() === $like->user->id ? route('home') : route('user.profile', $like->user->id) }}" 
                                       style="text-decoration: none; color: #2c3e50;">
                                        {{ $like->user->name }}
                                    </a>
                                </h6>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size:13px;">{{ $like->created_at->diffForHumans() }}</small>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection


