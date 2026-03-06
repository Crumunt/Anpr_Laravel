<?php

namespace App\Livewire\ANPR;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Profile Settings for security/ANPR users.
 */
#[Layout('layouts.anpr-layout')]
class ProfileSettings extends Component
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

    // Active tab
    public string $activeTab = 'profile';

    // User info
    public string $displayName = '';
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
        $this->user->load(['details', 'roles']);

        $this->email = $this->user->email;
        $this->currentEmail = $this->user->email;

        // Set display info
        $this->displayName = $this->user?->full_name ?? '';
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
            ]);

            $this->currentEmail = $this->email;

            $this->dispatch('toast', type: 'success', message: 'Email updated successfully.');

            Log::info('Security user email updated', [
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
     * Update password
     */
    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'min:8', PasswordRule::defaults()],
            'newPasswordConfirmation' => 'required|same:newPassword',
        ]);

        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return;
        }

        $this->updatingPassword = true;

        try {
            $this->user->update([
                'password' => Hash::make($this->newPassword),
                'must_change_password' => false,
            ]);

            $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);

            $this->dispatch('toast', type: 'success', message: 'Password updated successfully.');

            Log::info('Security user password updated', ['user_id' => $this->user->id]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to update password.');
            Log::error('Password update failed', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage()
            ]);
        } finally {
            $this->updatingPassword = false;
        }
    }

    public function render()
    {
        return view('livewire.anpr.profile-settings');
    }
}
