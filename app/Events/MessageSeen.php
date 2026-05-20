<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSeen implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $conversationId,
        public int $readerId,
        public array $messageIds
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }

    public function broadcastAs(): string
    {
        return 'message.seen';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'reader_id' => $this->readerId,
            'message_ids' => $this->messageIds,
        ];
    }
}
