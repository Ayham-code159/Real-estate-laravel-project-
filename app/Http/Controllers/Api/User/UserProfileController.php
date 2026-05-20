<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\User\UserProfileService;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\DeleteAccountRequest;

class UserProfileController extends Controller
{
    public function __construct(
        private UserProfileService $userProfileService
    ) {}

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userProfileService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->userProfileService->updatePassword(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Password updated successfully.',
            'data' => null,
        ]);
    }

    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        $this->userProfileService->deleteAccount(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Account deleted successfully.',
            'data' => null,
        ]);
    }
}
