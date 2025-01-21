@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4 class="mb-0">Recent Chats</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @php
                            $addedUsers = [];
                        @endphp
                        @forelse($recentChats as $chat)
                        @php
                            $chatUser = $chat->sender_id === auth()->id() ? $chat->receiver : $chat->sender;
                            if (in_array($chatUser->id, $addedUsers)) continue;
                            $addedUsers[] = $chatUser->id;
                            $hasUnread = $chat->sender_id !== auth()->id() && !$chat->is_read;
                        @endphp
                        <li class="list-group-item p-3 d-flex align-items-center {{ isset($activeUser) && $activeUser->id === $chatUser->id ? 'bg-light' : '' }}" data-chat-user-id="{{ $chatUser->id }}" id="chat-{{ $chatUser->id }}">
                            <a href="{{ route('chat.form', $chatUser->id) }}" class="text-decoration-none w-100 d-flex align-items-center">
                                <img src="{{ asset($chatUser->image ?? 'default.png') }}" alt="User Image" class="rounded-circle me-3" width="50" height="50">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark mx-2" style="font-size: 14px; font-weight:600">{{ $chatUser->name }}</span>
                                        <span class="text-muted text-truncate" style="font-size: 12px;">{{ $chat->last_message }}</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted" style="font-size: 11px;">{{ $chat->updated_at->diffForHumans() }}</span>
                                        @if($hasUnread)
                                            <span class="badge bg-danger ms-2 unread-badge">Unread</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                        @empty
                            <li class="list-group-item text-muted text-center">No recent chats</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
