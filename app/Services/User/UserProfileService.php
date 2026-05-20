<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileService
{
    public function updateProfile(User $user, array $data): User
    {
        $user->update([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
        ]);

        return $user->fresh();
    }

    public function updatePassword(User $user, array $data): void
    {
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => $data['password'],
        ]);
    }

    public function deleteAccount(User $user, array $data): void
    {
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        $user->delete();
    }
}
