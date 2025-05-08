<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index()
    {
        try {
            $conversations = Message::where('sender_id', auth()->id())
                ->orWhere('receiver_id', auth()->id())
                ->with(['sender', 'receiver'])
                ->latest()
                ->get()
                ->groupBy(function ($message) {
                    return $message->sender_id === auth()->id()
                        ? $message->receiver_id
                        : $message->sender_id;
                });

            $users = User::where('id', '!=', auth()->id())
                ->get()
                ->keyBy('id');

            $users->transform(function ($user) {
                $user->is_online = $user->is_online;
                return $user;
            });

            return view('messages.index', compact('conversations', 'users'));
        } catch (\Exception $e) {
            Log::error('Error in MessageController@index: ' . $e->getMessage());
            return response()->view('errors.general', [], 500);
        }
    }

    public function store(Request $request, User $user)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $message = Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $user->id,
                'message' => $request->message,
            ]);

            // Broadcast the event
            broadcast(new NewMessage($message))->toOthers();

            return response()->json([
                'data' => $message,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MessageController@store: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }


    public function fetchMessages(User $user)
    {
        try {
            $messages = Message::where(function ($query) use ($user) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })
                ->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', auth()->id());
                })
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'messages' => $messages,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in MessageController@fetchMessages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch messages'], 500);
        }
    }

    public function markAsRead(User $user)
    {
        try {
            Message::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error in MessageController@markAsRead: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to mark messages as read'], 500);
        }
    }
}
