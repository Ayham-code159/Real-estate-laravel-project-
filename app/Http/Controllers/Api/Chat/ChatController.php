<?php

namespace App\Http\Controllers\Api\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use App\Http\Requests\Chat\StoreMessageRequest;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService
    ) {}

    public function conversations(Request $request): JsonResponse
    {
        $conversations = $this->chatService->getMyConversations($request->user());

        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    public function startConversation(Request $request, int $userId): JsonResponse
    {
        $conversation = $this->chatService->getOrCreateConversation(
            $request->user(),
            $userId
        );

        return response()->json([
            'message' => 'Conversation ready.',
            'conversation' => $conversation->load(['userOne', 'userTwo']),
        ]);
    }

    public function messages(Request $request, int $conversationId): JsonResponse
    {
        $messages = $this->chatService->getMessages(
            $request->user(),
            $conversationId
        );

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function sendMessage(
        StoreMessageRequest $request,
        int $conversationId
    ): JsonResponse {
        $validated = $request->validated();

        $message = $this->chatService->sendMessage(
            $request->user(),
            $conversationId,
            $validated['body'] ?? null,
            $request->file('audio'),
            $request->file('image')
        );

        return response()->json([
            'message' => 'Message sent successfully.',
            'chat_message' => $message,
        ], 201);
    }

    public function reactToMessage(
        Request $request,
        int $conversationId,
        int $messageId
    ): JsonResponse {
        $validated = $request->validate([
            'emoji' => ['required', 'string', 'in:❤️,👍,😂,😮,😢'],
        ]);

        $message = $this->chatService->reactToMessage(
            $request->user(),
            $conversationId,
            $messageId,
            $validated['emoji']
        );

        return response()->json([
            'message' => 'Reaction updated successfully.',
            'chat_message' => $message,
        ]);
    }





    public function markAsRead(Request $request, int $conversationId): JsonResponse
    {
        $updatedCount = $this->chatService->markMessagesAsRead(
            $request->user(),
            $conversationId
        );

        return response()->json([
            'message' => 'Messages marked as read.',
            'updated_count' => $updatedCount,
        ]);
    }
}
