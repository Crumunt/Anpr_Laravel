<?php

namespace App\Services;

use App\Mail\WelcomeNewUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserInvitationService
{
    /**
     * Token expiration time in hours
     */
    protected int $tokenExpiresInHours = 48;

    /**
     * Send welcome email with password setup link to a new user
     *
     * @param User $user The user to send the invitation to
     * @return bool
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            // Generate a secure token
            $token = $this->createPasswordSetupToken($user);

            // Load user relationships for the email
            $user->load(['details', 'roles']);

            // Create the mailable
            $mailable = new WelcomeNewUser($user, $token, $this->tokenExpiresInHours);

            // Send email immediately
            Mail::to($user->email)->send($mailable);

            Log::info('Welcome email sent to new user', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Create a password setup token for the user
     *
     * @param User $user
     * @return string The plain token
     */
    protected function createPasswordSetupToken(User $user): string
    {
        // Generate a random token
        $token = Str::random(64);

        // Delete any existing tokens for this user
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        // Store the hashed token
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        Log::info('Password setup token created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'expires_at' => now()->addHours($this->tokenExpiresInHours),
        ]);

        return $token;
    }

    /**
     * Verify a password setup token
     *
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function verifyToken(string $email, string $token): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return false;
        }

        // Check if token is expired
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if ($createdAt->addHours($this->tokenExpiresInHours)->isPast()) {
            // Token expired, delete it
            $this->deleteToken($email);
            return false;
        }

        // Verify the token hash
        return Hash::check($token, $record->token);
    }

    /**
     * Delete a password setup token
     *
     * @param string $email
     * @return void
     */
    public function deleteToken(string $email): void
    {
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();
    }

    /**
     * Set the user's password and mark as no longer needing password change
     *
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function setUserPassword(User $user, string $password): bool
    {
        try {
            $user->update([
                'password' => Hash::make($password),
                'must_change_password' => false,
            ]);

            // Delete the token
            $this->deleteToken($user->email);

            Log::info('User password set via invitation link', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to set user password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Resend the welcome email to a user
     *
     * @param User $user
     * @return bool
     */
    public function resendWelcomeEmail(User $user): bool
    {
        // Only resend if user still needs to change password
        if (!$user->must_change_password) {
            Log::warning('Attempted to resend welcome email to user who already set password', [
                'user_id' => $user->id,
            ]);
            return false;
        }

        return $this->sendWelcomeEmail($user);
    }

    /**
     * Get token expiration time in hours
     *
     * @return int
     */
    public function getTokenExpirationHours(): int
    {
        return $this->tokenExpiresInHours;
    }
}
