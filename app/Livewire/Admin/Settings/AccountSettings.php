<?php

namespace App\Livewire\Admin\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Account Settings for the currently logged-in user.
 * This is the "My Account" page.
 */
#[Layout('layouts.app-layout')]
class AccountSettings extends Component
{
    // Current user
    public ?User $user = null;

    // Profile information
    public string $email = '';
    public string $currentEmail = '';

    // Password change
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    // Account status
    public bool $emailVerified = false;

    // Processing states
    public bool $updatingEmail = false;
    public bool $updatingPassword = false;
    public bool $processing = false;

    // Active tab
    public string $activeTab = 'profile';

    // User info
    public string $displayName = '';
    public string $role = '';
    public string $memberSince = '';

    /**
     * Mount the component with the authenticated user
     */
    public function mount(): void
    {
        $this->user = Auth::user();

        if (!$this->user) {
            return;
        }

        // Load user details relationship
        $this->user->load('details');

        $this->email = $this->user->email;
        $this->currentEmail = $this->user->email;
        $this->emailVerified = $this->user->email_verified_at !== null;

        // Set display info
        $this->displayName = $this->user->details?->full_name ?? $this->user->email;
        $this->role = $this->user->roles->first()?->name ?? 'User';
        $this->memberSince = $this->user->created_at?->format('F j, Y') ?? 'Unknown';
    }

    /**
     * Set the active tab
     */
    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetValidation();
        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
    }

    /**
     * Update email address
     */
    public function updateEmail(): void
    {
        $this->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
        ]);

        $this->updatingEmail = true;

        try {
            $oldEmail = $this->user->email;

            $this->user->update([
                'email' => $this->email,
                'email_verified_at' => null, // Require re-verification
            ]);

            $this->currentEmail = $this->email;
            $this->emailVerified = false;

            $this->dispatch('toast', type: 'success', message: 'Email updated successfully. Please verify your new email.');

            Log::info('User email updated', [
                'user_id' => $this->user->id,
                'old_email' => $oldEmail,
                'new_email' => $this->email,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to update email.');
            Log::error('Email update failed', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage()
            ]);
        } finally {
            $this->updatingEmail = false;
        }
    }

    /**
     * Send email verification link
     */
    public function sendVerificationEmail(): void
    {
        if ($this->emailVerified) {
            $this->dispatch('toast', type: 'info', message: 'Email is already verified.');
            return;
        }

        try {
            $this->user->sendEmailVerificationNotification();
            $this->dispatch('toast', type: 'success', message: 'Verification email sent.');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to send verification email.');
            Log::error('Verification email failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update password
     */
    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'min:8', PasswordRule::defaults()],
            'newPasswordConfirmation' => 'required|same:newPassword',
        ], [
            'newPasswordConfirmation.same' => 'The password confirmation does not match.',
        ]);

        // Verify current password
        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'The provided password does not match your current password.');
            return;
        }

        $this->updatingPassword = true;

        try {
            $this->user->update([
                'password' => Hash::make($this->newPassword),
            ]);

            $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
            $this->dispatch('toast', type: 'success', message: 'Password updated successfully.');

            Log::info('User password updated', ['user_id' => $this->user->id]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to update password.');
            Log::error('Password update failed', ['error' => $e->getMessage()]);
        } finally {
            $this->updatingPassword = false;
        }
    }

    /**
     * Request account deactivation (self-deactivation)
     */
    public function requestDeactivation(): void
    {
        $this->processing = true;

        try {
            $this->user->update(['is_active' => false]);

            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            Log::info('User self-deactivated', ['user_id' => $this->user->id]);

            $this->redirect(route('login'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to deactivate account.');
            $this->processing = false;
        }
    }

    /**
     * Request account deletion
     */
    public function requestDeletion(): void
    {
        $this->processing = true;

        try {
            $userId = $this->user->id;

            // Soft delete or hard delete based on your preference
            $this->user->delete();

            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            Log::info('User self-deleted account', ['user_id' => $userId]);

            $this->redirect(route('login'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to delete account.');
            Log::error('Account deletion failed', ['error' => $e->getMessage()]);
            $this->processing = false;
        }
    }

    /**
     * Log out of all other sessions
     */
    public function logoutOtherSessions(): void
    {
        $this->validate([
            'currentPassword' => 'required',
        ]);

        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'The provided password is incorrect.');
            return;
        }

        try {
            // Delete other sessions from the sessions table
            DB::table('sessions')
                ->where('user_id', $this->user->id)
                ->where('id', '!=', session()->getId())
                ->delete();

            $this->reset('currentPassword');
            $this->dispatch('toast', type: 'success', message: 'Logged out of all other sessions.');

            Log::info('User logged out other sessions', ['user_id' => $this->user->id]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to log out other sessions.');
            Log::error('Logout other sessions failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get formatted role display name
     */
    public function getRoleDisplayName(): string
    {
        $roleNames = [
            'super_admin' => 'Super Administrator',
            'admin_editor' => 'Administrator (Editor)',
            'admin_viewer' => 'Administrator (Viewer)',
            'encoder' => 'Encoder',
            'security' => 'Security Personnel',
            'maintenance' => 'Maintenance Staff',
            'applicant' => 'Applicant',
        ];

        return $roleNames[$this->role] ?? ucwords(str_replace('_', ' ', $this->role));
    }

    public function render()
    {
        return view('livewire.admin.settings.account-settings');
    }
}
