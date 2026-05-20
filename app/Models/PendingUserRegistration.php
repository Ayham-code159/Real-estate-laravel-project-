<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingUserRegistration extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'identifier',
        'identifier_type',
        'email',
        'phone',
        'password',
        'token',
        'expires_at',
        'last_sent_at',
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_sent_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function canResend(): bool
    {
        return ! $this->last_sent_at || now()->diffInSeconds($this->last_sent_at) >= 60;
    }
}
