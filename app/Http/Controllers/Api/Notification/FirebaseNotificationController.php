<?php

namespace App\Http\Controllers\Api\Notification;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Notification\FirebaseNotificationService;

class FirebaseNotificationController extends Controller
{
    public function __construct(
        private FirebaseNotificationService $firebaseNotificationService
    ) {}

    public function sendToAuthenticatedUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'data' => ['nullable', 'array'],
        ]);

        $result = $this->firebaseNotificationService->sendToUser(
            $request->user(),
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? []
        );

        return response()->json($result);
    }

    public function sendToUserById(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'data' => ['nullable', 'array'],
        ]);

        $user = User::query()->findOrFail($userId);

        $result = $this->firebaseNotificationService->sendToUser(
            $user,
            $validated['title'],
            $validated['body'],
            $validated['data'] ?? []
        );

        return response()->json($result);
    }
}
