<?php
namespace App\Http\Controllers\Api\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Notification\DeviceTokenService;

class DeviceTokenController extends Controller
{
    public function __construct(
        private DeviceTokenService $deviceTokenService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'device_type' => ['nullable', 'string'],
        ]);

        $this->deviceTokenService->store(
            $request->user(),
            $validated['token'],
            $validated['device_type'] ?? null
        );

        return response()->json([
            'message' => 'Device token saved successfully.'
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $this->deviceTokenService->delete(
            $request->user(),
            $validated['token']
        );

        return response()->json([
            'message' => 'Device token removed successfully.'
        ]);
    }
}
