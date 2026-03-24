<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthService
{
    public function register(array $data): array
    {
        $identifierData = $this->parseIdentifier($data['identifier']);

        $this->ensureIdentifierIsUnique($identifierData);

        $username = $this->generateUniqueUsername(
            $data['first_name'],
            $data['last_name']
        );

        $user = User::create([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'username' => $username,
            'email' => $identifierData['type'] === 'email' ? $identifierData['value'] : null,
            'phone' => $identifierData['type'] === 'phone' ? $identifierData['value'] : null,
            'password' => $data['password'],
            'status' => 'active',
        ]);

        $token = $user->createToken('user-register-token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
            'identifier_type' => $identifierData['type'],
        ];
    }

    public function login(array $data): array
    {
        $identifierData = $this->parseIdentifier($data['identifier']);

        $user = $identifierData['type'] === 'email'
            ? User::where('email', $identifierData['value'])->first()
            : User::where('phone', $identifierData['value'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => ['Invalid credentials.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'identifier' => ['This account is not active.'],
            ]);
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        $token = $user->createToken('user-login-token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $token = $user->token();

        if ($token) {
            $token->delete();
        }
    }

    private function parseIdentifier(string $identifier): array
    {
        $identifier = trim($identifier);

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return [
                'type' => 'email',
                'value' => strtolower($identifier),
            ];
        }

        $normalizedPhone = $this->normalizePhone($identifier);

        if (! $this->isValidPhone($normalizedPhone)) {
            throw ValidationException::withMessages([
                'identifier' => ['Please enter a valid email address or phone number.'],
            ]);
        }

        return [
            'type' => 'phone',
            'value' => $normalizedPhone,
        ];
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\s+/', '', $phone);
        $phone = str_replace(['-', '(', ')'], '', $phone);

        return $phone;
    }

    private function isValidPhone(string $phone): bool
    {
        return preg_match('/^\+?[0-9]{8,15}$/', $phone) === 1;
    }

    private function ensureIdentifierIsUnique(array $identifierData): void
    {
        $exists = $identifierData['type'] === 'email'
            ? User::where('email', $identifierData['value'])->exists()
            : User::where('phone', $identifierData['value'])->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'identifier' => ['This email or phone number is already registered.'],
            ]);
        }
    }

    private function generateUniqueUsername(string $firstName, string $lastName): string
    {
        $baseUsername = Str::slug($firstName . ' ' . $lastName, '_');

        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }

        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }

        return $username;
    }
}
