<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;

class ChatSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public User $receiver;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender', 'receiver');  // Load sender and receiver relationships
    }

    public function broadcastOn()
    {
        return new Channel('chat.' . $this->message->receiver_id);
    }

    public function broadcastWith()
{
    return [
        'id' => $this->message->id,
        'sender' => [
            'id' => $this->message->sender->id,
            'name' => $this->message->sender->name,
            'image' => $this->message->sender->image,
        ],
        'receiver' => [
            'id' => $this->message->receiver->id,
            'name' => $this->message->receiver->name,
            'image' => $this->message->receiver->image,
        ],
        'content' => $this->message->content,
        'created_at' => $this->message->created_at->toDateTimeString(),
        'is_read' => $this->message->is_read,
    ];
}

    /**
     * Alias the event for the frontend.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'chat.message.sent';
    }
}


