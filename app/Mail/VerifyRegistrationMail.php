<?php

namespace App\Mail;

use App\Models\PendingUserRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PendingUserRegistration $pendingRegistration
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Verify your Servixa account')
            ->view('emails.verify-registration')
            ->with([
                'verificationUrl' => rtrim(config('app.url'), '/') . '/api/auth/verify-registration/' . $this->pendingRegistration->token,
                'expiresAt' => $this->pendingRegistration->expires_at,
                'firstName' => $this->pendingRegistration->first_name,
            ]);
    }
}
