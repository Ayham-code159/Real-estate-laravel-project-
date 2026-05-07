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
        $message = $this->chatService->sendMessage(
            $request->user(),
            $conversationId,
            $request->validated()['body']
        );

        return response()->json([
            'message' => 'Message sent successfully.',
            'chat_message' => $message,
        ], 201);
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
