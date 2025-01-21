@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 border-end">
            <h4 class="mt-3">Recent Chats</h4>
            <ul class="list-group">
                @php $addedUsers = []; @endphp
                @forelse($recentChats as $chat)
                @php
                    $chatUser = $chat->sender_id === auth()->id() ? $chat->receiver : $chat->sender;
                    if (in_array($chatUser->id, $addedUsers)) continue;
                    $addedUsers[] = $chatUser->id;
                    $hasUnread = $chat->sender_id !== auth()->id() && !$chat->is_read;
                @endphp
                <li class="list-group-item p-2 {{ isset($activeUser) && $activeUser->id === $chatUser->id ? 'active' : '' }}" 
                    data-chat-user-id="{{ $chatUser->id }}" 
                    id="chat-{{ $chatUser->id }}">
                    <a href="{{ route('chat.form', $chatUser->id) }}" class="text-decoration-none">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($chatUser->image ?? 'default.png') }}" alt="User Image" class="rounded-circle me-2" width="50" height="50">
                            <span class="fw-bold mx-2 text-dark" style="font-family: Nunito, sans-serif; font-size:14px; font-weight:700;">{{ $chatUser->name }}</span>
                            @if($hasUnread)
                                <span class="badge bg-danger ms-2 unread-badge">Unread</span>
                            @endif
                        </div>
                    </a>
                </li>
                @empty
                    <li class="list-group-item text-muted">No recent chats</li>
                @endforelse
            </ul>
        </div>

        <div class="col-md-8">
            <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                <img src="{{ asset($activeUser->image ?? 'default.png') }}" alt="User Image" class="rounded-circle me-3" width="60" height="60">
                <div class="ms-3" style="font-family: Nunito, sans-serif;">
                    <h5 class="mb-1 mx-2" style="font-size:16px; font-weight:600;">{{ $activeUser->name }}</h5>
                </div>
            </div>
            <div class="chat-window border p-3 mb-3" style="height: 500px; overflow-y: auto;">
                <ul id="messagesList" class="list-unstyled">
                    @if($messages->isEmpty())
                        <li class="text-muted text-center" id="noMessagesText">No messages yet. Start the conversation!</li>
                    @else
                        @foreach($messages as $message)
                            <li class="mb-3 d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="card {{ $message->sender_id == auth()->id() ? 'bg-light' : 'bg-white' }}" style="max-width: 60%;">
                                    <div class="card-header d-flex align-items-center" style="max-width: 100%; white-space: nowrap;">
                                        <img src="{{ asset($message->sender->image ?? 'default.png') }}" class="rounded-circle me-2" width="30" height="30">
                                        <span class="mx-2" style="font-family: Nunito, sans-serif; font-size: 12px;">{{ $message->sender_id == auth()->id() ? 'You' : $activeUser->name }}</span>
                                    </div>
                                    <div class="card-body" style="word-wrap: break-word;">
                                        <p class="mb-1">{{ $message->content }}</p>
                                        <span class="text-muted">{{ $message->created_at->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <form id="messageForm">
                <div class="input-group">
                    <input type="text" id="messageInput" class="form-control" placeholder="Type your message..." required>
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const chatWindow = document.querySelector('.chat-window');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const messagesList = document.getElementById('messagesList');
    const currentUserId = {{ Auth::id() }};
    const recipientId = {{ $activeUser->id }};

    function scrollToBottom() {
        if (chatWindow) {
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }
    }

    function isCurrentUser(senderId) {
        return senderId === currentUserId;
    }

    function appendMessage(message, isSender) {
        const li = document.createElement('li');
        li.classList.add('mb-3', 'd-flex', isSender ? 'justify-content-end' : 'justify-content-start');

        const card = document.createElement('div');
        card.classList.add('card', isSender ? 'bg-light' : 'bg-white');
        card.style.maxWidth = '60%';

        const cardHeader = document.createElement('div');
        cardHeader.classList.add('card-header', 'd-flex', 'align-items-center');
        cardHeader.style.maxWidth = '100%';
        cardHeader.style.whiteSpace = 'nowrap';

        const img = document.createElement('img');
        img.src = isSender
            ? `{{ asset(Auth::user()->image ?? 'default.png') }}`
            : `{{ asset($activeUser->image ?? 'default.png') }}`;
        img.alt = 'User Image';
        img.classList.add('rounded-circle', 'me-2');
        img.width = 30;
        img.height = 30;

        const senderName = document.createElement('span');
        senderName.textContent = isSender ? 'You' : '{{ $activeUser->name }}';
        senderName.classList.add('mx-2');
        senderName.style.fontFamily = "'Nunito', sans-serif";
        senderName.style.fontSize = '12px';

        cardHeader.append(img);
        cardHeader.append(senderName);

        const cardBody = document.createElement('div');
        cardBody.classList.add('card-body');
        cardBody.style.wordWrap = 'break-word';

        const messageContent = document.createElement('p');
        messageContent.classList.add('mb-1');
        messageContent.textContent = message.content;

        const messageTime = document.createElement('span');
        messageTime.textContent = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        messageTime.classList.add('text-muted', 'd-block', 'mt-1');
        messageTime.style.fontSize = '10px';

        cardBody.append(messageContent);
        cardBody.append(messageTime);
        card.append(cardHeader);
        card.append(cardBody);
        li.append(card);

        const noMessagesText = document.getElementById('noMessagesText');
        if (noMessagesText) {
            noMessagesText.remove();
        }

        messagesList.append(li);
        scrollToBottom();
    }

    async function loadInitialMessages() {
        try {
            const response = await fetch(`/chat/${recipientId}`);
            const data = await response.json();

            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(message => {
                    appendMessage(message, isCurrentUser(message.sender_id));
                });
            } else {
                const noMessagesText = document.createElement('p');
                noMessagesText.classList.add('text-muted', 'text-center');
                noMessagesText.id = 'noMessagesText';
                noMessagesText.textContent = 'No messages yet. Start the conversation!';
                messagesList.appendChild(noMessagesText);
            }
        } catch (error) {
            console.error('Error fetching initial messages:', error);
        }
    }

    messageForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) return;

        try {
            const response = await fetch(`/chat/${recipientId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ content: message, recipient_id: recipientId }),
            });
            const data = await response.json();

            if (data.status === 'Message sent successfully') {
                messageInput.value = '';
                appendMessage(data.message, true);
                scrollToBottom();
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });

    loadInitialMessages();
</script>
@endsection


