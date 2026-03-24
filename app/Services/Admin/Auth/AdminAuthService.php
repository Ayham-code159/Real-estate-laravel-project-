<?php

namespace App\Services\Admin\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthService
{
    public function login(array $data): void
    {
        $admin = Admin::where('email', $data['email'])->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        if ($admin->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['This account is inactive.'],
            ]);
        }

        Auth::guard('admin')->login($admin);

        $admin->update([
            'last_login_at' => now(),
        ]);
    }

    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
