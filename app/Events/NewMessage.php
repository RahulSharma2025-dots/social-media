<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->receiver_id);
    }

    public function broadcastWith()
    {
        try {
            return [
                'message' => $this->message->load('sender'),
            ];
        } catch (\Exception $e) {
            Log::error('Error in NewMessage@broadcastWith: ' . $e->getMessage());
            return [
                'error' => 'Failed to load message data',
            ];
        }
    }
} 