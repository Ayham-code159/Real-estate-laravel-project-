<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\UserAuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class UserAuthController extends Controller
{
    public function __construct(
        private UserAuthService $userAuthService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->userAuthService->register($request->validated());

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $result['user'],
            'token' => $result['token'],
            'identifier_type' => $result['identifier_type'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->userAuthService->login($request->validated());

        return response()->json([
            'message' => 'Login successful.',
            'user' => $result['user'],
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->userAuthService->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }


    // note: clean the functions from the return type
}
