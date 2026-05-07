<?php

namespace App\Services\Chat;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use App\Events\MessageSent;

class ChatService
{
    public function getOrCreateConversation(User $authUser, int $otherUserId): Conversation
    {
        if ($authUser->id === $otherUserId) {
            throw ValidationException::withMessages([
                'user_id' => ['You cannot start a conversation with yourself.'],
            ]);
        }

        $otherUser = User::query()->findOrFail($otherUserId);

        $userOneId = min($authUser->id, $otherUser->id);
        $userTwoId = max($authUser->id, $otherUser->id);

        return Conversation::query()->firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);
    }

    public function getMyConversations(User $authUser): Collection
    {
        return Conversation::query()
            ->with([
                'userOne',
                'userTwo',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                },
            ])
            ->where('user_one_id', $authUser->id)
            ->orWhere('user_two_id', $authUser->id)
            ->latest()
            ->get();
    }

    public function getMessages(User $authUser, int $conversationId): Collection
    {
        $conversation = $this->getConversationForUser($authUser, $conversationId);

        return $conversation->messages()
            ->with('sender')
            ->oldest()
            ->get();
    }

    public function sendMessage(User $authUser, int $conversationId, string $body): Message
    {
        $conversation = $this->getConversationForUser($authUser, $conversationId);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $authUser->id,
            'body' => trim($body),
        ])->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }



    public function markMessagesAsRead(User $authUser, int $conversationId): int
    {
        $conversation = $this->getConversationForUser($authUser, $conversationId);

        return $conversation->messages()
            ->where('sender_id', '!=', $authUser->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
    }

    private function getConversationForUser(User $authUser, int $conversationId): Conversation
    {
        $conversation = Conversation::query()->findOrFail($conversationId);

        if ($conversation->user_one_id !== $authUser->id && $conversation->user_two_id !== $authUser->id) {
            throw ValidationException::withMessages([
                'conversation' => ['This conversation does not belong to you.'],
            ]);
        }

        return $conversation;
    }
}
