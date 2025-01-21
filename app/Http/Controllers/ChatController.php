<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatSent;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chatform($id)
    {
        $authUserId = auth()->id();

        if (!$authUserId || !$id) {
            abort(400, 'Invalid user IDs provided.');
        }

        $recentChats = Message::where('sender_id', $authUserId)
            ->orWhere('recipient_id', $authUserId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($message) use ($authUserId) {
                return $message->sender_id === $authUserId ? $message->recipient_id : $message->sender_id;
            })
            ->map(function ($chat) use ($authUserId) {
                $otherUserId = $chat->sender_id === $authUserId ? $chat->recipient_id : $chat->sender_id;

                $chat->unread_count = Message::where('sender_id', $otherUserId)
                    ->where('recipient_id', $authUserId)
                    ->where('is_read', false)
                    ->count();

                return $chat;
            });

        Log::info('Recent Chats:', $recentChats->toArray());

        $messages = Message::where(function ($query) use ($authUserId, $id) {
            $query->where('sender_id', $authUserId)
                  ->where('recipient_id', $id);
        })
            ->orWhere(function ($query) use ($authUserId, $id) {
                $query->where('sender_id', $id)
                      ->where('recipient_id', $authUserId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        Log::info('Active Messages:', $messages->toArray());

        $updatedCount = Message::where('sender_id', $id)
            ->where('recipient_id', $authUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        Log::info("Messages marked as read: $updatedCount");

        $activeUser = User::find($id);
        if (!$activeUser) {
            abort(404, 'User not found.');
        }

        Log::info('Active User:', $activeUser->toArray());

        return view('instafeed.chat', compact('recentChats', 'messages', 'activeUser'));
    }

    public function sendMessage(Request $request, $recipientId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $authUserId = auth()->id();

        $isFirstMessage = !Message::where(function ($query) use ($authUserId, $recipientId) {
            $query->where('sender_id', $authUserId)->where('recipient_id', $recipientId);
        })->orWhere(function ($query) use ($authUserId, $recipientId) {
            $query->where('sender_id', $recipientId)->where('recipient_id', $authUserId);
        })->exists();

        $message = Message::create([
            'sender_id' => $authUserId,
            'recipient_id' => $recipientId,
            'content' => $request->content,
            'is_read' => false,
        ]);

        if ($message) {
            broadcast(new ChatSent($message))->toOthers();
            Log::info('Broadcasted message:', ['message_id' => $message->id]);
        }

        Log::info('Message Sent:', [
            'message' => $message->toArray(),
            'isFirstMessage' => $isFirstMessage,
        ]);

        return response()->json([
            'status' => 'Message sent successfully',
            'message' => $message,
            'first_message' => $isFirstMessage,
        ]);
    }

    public function markAsRead($id)
    {
        $authUserId = auth()->id();

        $updatedCount = Message::where('sender_id', $id)
            ->where('recipient_id', $authUserId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        Log::info("Messages marked as read: $updatedCount for conversation with user ID: $id");

        return response()->json(['status' => 'Messages marked as read']);
    }

    public function getchat(){
        $recentChats = Message::where('sender_id', auth()->id())
            ->orWhere('recipient_id', auth()->id())
            ->with(['sender', 'receiver'])
            ->latest() 
            ->get()
            ->unique(function ($item) {
                return $item->sender_id == auth()->id() ? $item->recipient_id : $item->sender_id;
            });
    
        return view('instafeed.chatlist', compact('recentChats'));
    }
    
}
