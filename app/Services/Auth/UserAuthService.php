<?php

namespace App\Services\Auth;

use App\Mail\VerifyRegistrationMail;
use App\Models\PendingUserRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserAuthService
{
    public function register(array $data): array
    {
        $identifierData = $this->parseIdentifier($data['identifier']);

        $this->ensureIdentifierIsUnique($identifierData);

        if ($identifierData['type'] === 'phone') {
            return $this->registerPhoneUserImmediately($data, $identifierData);
        }

        $email = $identifierData['value'];

        $pendingRegistration = PendingUserRegistration::query()
            ->where('email', $email)
            ->latest()
            ->first();

        if ($pendingRegistration && ! $pendingRegistration->isExpired() && ! $pendingRegistration->canResend()) {
            throw ValidationException::withMessages([
                'identifier' => ['Please wait 1 minute before requesting another verification email.'],
            ]);
        }

        PendingUserRegistration::query()
            ->where('email', $email)
            ->delete();

        $pendingRegistration = PendingUserRegistration::create([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'identifier' => $email,
            'identifier_type' => 'email',
            'email' => $email,
            'phone' => null,
            'password' => $data['password'],
            'token' => Str::random(80),
            'expires_at' => now()->addMinutes(5),
            'last_sent_at' => now(),
        ]);

        Mail::to($email)->send(new VerifyRegistrationMail($pendingRegistration));

        return [
            'pending_registration' => $pendingRegistration,
            'identifier_type' => 'email',
            'requires_email_verification' => true,
        ];
    }

    public function verifyRegistration(string $token): array
    {
        $pendingRegistration = PendingUserRegistration::query()
            ->where('token', $token)
            ->first();

        if (! $pendingRegistration) {
            throw ValidationException::withMessages([
                'token' => ['Invalid verification link.'],
            ]);
        }

        if ($pendingRegistration->isExpired()) {
            $pendingRegistration->delete();

            throw ValidationException::withMessages([
                'token' => ['Verification link expired. Please register again.'],
            ]);
        }

        $identifierData = [
            'type' => $pendingRegistration->identifier_type,
            'value' => $pendingRegistration->identifier,
        ];

        $this->ensureIdentifierIsUnique($identifierData);

        $username = $this->generateUniqueUsername(
            $pendingRegistration->first_name,
            $pendingRegistration->last_name
        );

        $user = User::create([
            'first_name' => $pendingRegistration->first_name,
            'last_name' => $pendingRegistration->last_name,
            'username' => $username,
            'email' => $pendingRegistration->email,
            'phone' => null,
            'password' => $pendingRegistration->password,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $pendingRegistration->delete();

        $token = $user->createToken('user-register-token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
            'identifier_type' => 'email',
        ];
    }

    public function resendVerificationEmail(string $identifier): array
    {
        $identifierData = $this->parseIdentifier($identifier);

        if ($identifierData['type'] !== 'email') {
            throw ValidationException::withMessages([
                'identifier' => ['Verification resend is only available for email registrations.'],
            ]);
        }

        $email = $identifierData['value'];

        $this->ensureIdentifierIsUnique($identifierData);

        $pendingRegistration = PendingUserRegistration::query()
            ->where('email', $email)
            ->latest()
            ->first();

        if (! $pendingRegistration) {
            throw ValidationException::withMessages([
                'identifier' => ['No pending registration found for this email. Please register again.'],
            ]);
        }

        if ($pendingRegistration->isExpired()) {
            $pendingRegistration->delete();

            throw ValidationException::withMessages([
                'identifier' => ['Verification link expired. Please register again.'],
            ]);
        }

        if (! $pendingRegistration->canResend()) {
            throw ValidationException::withMessages([
                'identifier' => ['Please wait 1 minute before requesting another verification email.'],
            ]);
        }

        $pendingRegistration->update([
            'token' => Str::random(80),
            'expires_at' => now()->addMinutes(5),
            'last_sent_at' => now(),
        ]);

        Mail::to($email)->send(new VerifyRegistrationMail($pendingRegistration->fresh()));

        return [
            'identifier_type' => 'email',
            'requires_email_verification' => true,
        ];
    }

    private function registerPhoneUserImmediately(array $data, array $identifierData): array
    {
        $username = $this->generateUniqueUsername(
            $data['first_name'],
            $data['last_name']
        );

        $user = User::create([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'username' => $username,
            'email' => null,
            'phone' => $identifierData['value'],
            'password' => $data['password'],
            'status' => 'active',
        ]);

        $token = $user->createToken('user-register-token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
            'identifier_type' => 'phone',
            'requires_email_verification' => false,
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
