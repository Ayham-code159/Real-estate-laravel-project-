<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\UserAuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Notification\DeviceTokenService;

class UserAuthController extends Controller
{
    public function __construct(
        private UserAuthService $userAuthService,
        private DeviceTokenService $deviceTokenService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->userAuthService->register($data);

        if (($result['requires_email_verification'] ?? false) === true) {
            return response()->json([
                'message' => 'Verification email sent. Please verify your email within 5 minutes.',
                'data' => [
                    'requires_email_verification' => true,
                    'identifier_type' => $result['identifier_type'],
                ],
            ], 202);
        }

        if (! empty($data['device_token'])) {
            $this->deviceTokenService->store(
                $result['user'],
                $data['device_token'],
                $data['device_type'] ?? null
            );
        }

        return response()->json([
            'message' => 'User registered successfully.',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
                'identifier_type' => $result['identifier_type'],
            ],
        ], 201);
    }

    public function verifyRegistration(string $token): JsonResponse
    {
        $result = $this->userAuthService->verifyRegistration($token);

        return response()->json([
            'message' => 'Email verified successfully. Account created.',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
                'identifier_type' => $result['identifier_type'],
            ],
        ], 201);
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ]);

        $result = $this->userAuthService->resendVerificationEmail(
            $validated['identifier']
        );

        return response()->json([
            'message' => 'Verification email resent. Please verify your email within 5 minutes.',
            'data' => [
                'requires_email_verification' => true,
                'identifier_type' => $result['identifier_type'],
            ],
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->userAuthService->login($data);

        if (! empty($data['device_token'])) {
            $this->deviceTokenService->store(
                $result['user'],
                $data['device_token'],
                $data['device_type'] ?? null
            );
        }

        return response()->json([
            'message' => 'Login successful.',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->filled('device_token')) {
            $this->deviceTokenService->delete(
                $request->user(),
                $request->input('device_token')
            );
        }

        $this->userAuthService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully.',
            'data' => null,
        ]);
    }
}
