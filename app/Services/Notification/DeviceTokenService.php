<?php
namespace App\Services\Notification;

use App\Models\User;
use App\Models\DeviceToken;

class DeviceTokenService
{
    public function store(User $user, string $token, ?string $deviceType = null): DeviceToken
    {
        return DeviceToken::updateOrCreate(
            ['token' => $token],
            [
                'user_id' => $user->id,
                'device_type' => $deviceType,
            ]
        );
    }

    public function delete(User $user, string $token): void
    {
        DeviceToken::where('user_id', $user->id)
            ->where('token', $token)
            ->delete();
    }
}
