<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class MessageReactionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->message->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'message.reaction.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->message->id,
            'reactions' => $this->message->reactions()
                ->select('id', 'message_id', 'user_id', 'emoji')
                ->get(),
        ];
    }
}
