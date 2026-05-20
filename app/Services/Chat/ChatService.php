<?php

namespace App\Services\Chat;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;
use App\Events\MessageSeen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use App\Events\MessageReactionUpdated;

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
            ->with(['sender', 'reactions'])
            ->oldest()
            ->get();
        }

    public function sendMessage(
    User $authUser,
    int $conversationId,
    ?string $body = null,
    $audio = null,
    $image = null
    ): Message {
        $conversation = $this->getConversationForUser($authUser, $conversationId);

        $audioPath = null;
        $imagePath = null;
        $messageType = 'text';

        if ($audio) {
            $audioPath = $audio->store('chat/audio', 'public');
            $messageType = 'voice';
        }

        if ($image) {
            $imagePath = $image->store('chat/images', 'public');
            $messageType = 'image';
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $authUser->id,
            'message_type' => $messageType,
            'body' => $body ? trim($body) : null,
            'audio_path' => $audioPath,
            'image_path' => $imagePath,
        ])->load(['sender', 'reactions']);

        broadcast(new MessageSent($message));

        return $message;
    }

    public function reactToMessage(
    User $authUser,
    int $conversationId,
    int $messageId,
    string $emoji
    ): Message {
        $conversation = $this->getConversationForUser($authUser, $conversationId);

        $allowedEmojis = ['❤️', '👍', '😂', '😮', '😢'];

        if (! in_array($emoji, $allowedEmojis, true)) {
            throw ValidationException::withMessages([
                'emoji' => ['Invalid reaction emoji.'],
            ]);
        }

        $message = $conversation->messages()
            ->where('id', $messageId)
            ->first();

        if (! $message) {
            throw ValidationException::withMessages([
                'message' => ['Message not found.'],
            ]);
        }

        $existingReaction = $message->reactions()
            ->where('user_id', $authUser->id)
            ->first();

        if ($existingReaction && $existingReaction->emoji === $emoji) {
            $existingReaction->delete();
        } else {
            $message->reactions()->updateOrCreate(
                [
                    'user_id' => $authUser->id,
                ],
                [
                    'emoji' => $emoji,
                ]
            );
        }

        $message = $message->fresh()->load(['sender', 'reactions']);

        broadcast(new MessageReactionUpdated($message));

        return $message;
    }




    public function markMessagesAsRead(User $authUser, int $conversationId): int
    {
        $conversation = $this->getConversationForUser($authUser, $conversationId);

        $messageIds = $conversation->messages()
            ->where('sender_id', '!=', $authUser->id)
            ->whereNull('read_at')
            ->pluck('id')
            ->toArray();

        if (empty($messageIds)) {
            return 0;
        }

        $updatedCount = Message::query()
            ->whereIn('id', $messageIds)
            ->update([
                'read_at' => now(),
            ]);

        broadcast(new MessageSeen(
            $conversation->id,
            $authUser->id,
            $messageIds
        ));

        return $updatedCount;
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
