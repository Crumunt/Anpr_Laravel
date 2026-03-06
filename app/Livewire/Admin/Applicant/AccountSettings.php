<?php

namespace App\Livewire\Admin\Applicant;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Account Settings for the currently logged-in user.
 * This is the "Manage My Account" page.
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

    // Processing states
    public bool $updatingEmail = false;
    public bool $updatingPassword = false;
    public bool $processing = false;

    // Active tab
    public string $activeTab = 'profile';

    /**
     * Mount the component with temp test data for testing purposes
     */
    public function mount(): void
    {
        // TEMP: Use mock data for testing instead of Auth::user()
        // TODO: Revert to Auth::user() for production
        $this->user = new User([
            'id' => 1,
            'email' => 'test.admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Set a fake ID for the mock user
        $this->user->id = 1;

        // Mock the details relationship data
        $this->user->setRelation('details', (object) [
            'full_name' => 'Test Administrator',
            'first_name' => 'Test',
            'last_name' => 'Administrator',
        ]);

        $this->email = $this->user->email;
        $this->currentEmail = $this->user->email;
    }

    /**
     * Set the active tab
     */
    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    /**
     * Update email address
     */
    public function updateEmail(): void
    {
        $this->validate([
            'email' => 'required|email|max:255',
        ]);

        $this->updatingEmail = true;

        try {
            // TEMP: Mock update for testing - just update local state
            $oldEmail = $this->currentEmail;
            $this->currentEmail = $this->email;

            $this->dispatch('toast', type: 'success', message: '[TEST MODE] Email updated successfully.');

            Log::info('[TEST] User email updated', [
                'old_email' => $oldEmail,
                'new_email' => $this->email,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to update email.');
            Log::error('Email update failed', ['error' => $e->getMessage()]);
        } finally {
            $this->updatingEmail = false;
        }
    }

    /**
     * Update password
     */
    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'min:8'],
            'newPasswordConfirmation' => 'required|same:newPassword',
        ], [
            'newPasswordConfirmation.same' => 'The password confirmation does not match.',
        ]);

        // TEMP: For testing, accept 'password' as the current password
        if ($this->currentPassword !== 'password') {
            $this->addError('currentPassword', '[TEST MODE] Use "password" as the current password.');
            return;
        }

        $this->updatingPassword = true;

        try {
            // TEMP: Mock password update for testing
            $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);

            $this->dispatch('toast', type: 'success', message: '[TEST MODE] Password updated successfully.');

            Log::info('[TEST] User password updated');
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
            // TEMP: Mock deactivation for testing - don't actually logout or redirect
            $this->dispatch('toast', type: 'success', message: '[TEST MODE] Your account would be deactivated.');

            Log::info('[TEST] User self-deactivated');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to deactivate account.');
        } finally {
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
            // TEMP: Mock deletion for testing - don't actually delete or redirect
            $this->dispatch('toast', type: 'success', message: '[TEST MODE] Your account would be deleted.');

            Log::info('[TEST] User self-deleted account');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to delete account.');
            Log::error('Account deletion failed', ['error' => $e->getMessage()]);
        } finally {
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

        // TEMP: For testing, accept 'password' as the password
        if ($this->currentPassword !== 'password') {
            $this->addError('currentPassword', '[TEST MODE] Use "password" as the password.');
            return;
        }

        try {
            // TEMP: Mock logout other sessions for testing
            $this->reset('currentPassword');
            $this->dispatch('toast', type: 'success', message: '[TEST MODE] Logged out of all other sessions.');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to log out other sessions.');
        }
    }

    public function render()
    {
        return view('livewire.admin.applicant.account-settings');
    }
}
