<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUser extends Mailable
{
    use SerializesModels;

    /**
     * The password setup URL with secure token
     */
    public string $setupUrl;

    /**
     * The user's display name
     */
    public string $userName;

    /**
     * The user's role for display
     */
    public string $userRole;

    /**
     * Token expiration time in hours
     */
    public int $expiresInHours;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $user,
        string $token,
        int $expiresInHours = 48
    ) {
        $this->userName = $user->details?->first_name ?? 'User';
        $this->userRole = $this->formatRoleName($user->roles->first()?->name ?? 'user');
        $this->expiresInHours = $expiresInHours;
        $this->setupUrl = route('password.setup', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    /**
     * Format role name for display
     */
    private function formatRoleName(string $role): string
    {
        return ucwords(str_replace('_', ' ', $role));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name') . ' - Set Up Your Password',
            from: new Address(
                config('mail.from.address', 'no-reply@keenpass.test'),
                config('mail.from.name', config('app.name', 'KeenPass'))
            )
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-new-user',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
