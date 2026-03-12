<?php

namespace App\Livewire\ANPR;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public bool $updatingProfile = false;

    // Active tab
    public string $activeTab = 'profile';

    // User info
    public string $displayName = '';
    public string $memberSince = '';

    // Profile editing fields
    public string $firstName = '';
    public string $middleName = '';
    public string $lastName = '';
    public string $suffix = '';
    public string $phoneNumber = '';

    // Edit mode toggle
    public bool $isEditingProfile = false;

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
        $this->displayName = $this->user->details?->full_name ?? $this->user->email;
        $this->memberSince = $this->user->created_at?->format('F j, Y') ?? 'Unknown';

        // Load profile details
        $this->loadProfileDetails();
    }

    /**
     * Load profile details from user details
     */
    protected function loadProfileDetails(): void
    {
        $details = $this->user->details;

        if ($details) {
            $this->firstName = $details->first_name ?? '';
            $this->middleName = $details->middle_name ?? '';
            $this->lastName = $details->last_name ?? '';
            $this->suffix = $details->suffix ?? '';
            $this->phoneNumber = $details->phone_number ?? '';
        }
    }

    /**
     * Toggle profile editing mode
     */
    public function toggleEditProfile(): void
    {
        $this->isEditingProfile = !$this->isEditingProfile;

        if (!$this->isEditingProfile) {
            // Reset to original values when canceling
            $this->loadProfileDetails();
            $this->resetValidation();
        }
    }

    /**
     * Update profile information
     */
    public function updateProfile(): void
    {
        $this->validate([
            'firstName' => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:20',
            'phoneNumber' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]*$/',
        ], [
            'firstName.required' => 'First name is required.',
            'lastName.required' => 'Last name is required.',
            'phoneNumber.regex' => 'Please enter a valid phone number.',
        ]);

        $this->updatingProfile = true;

        try {
            DB::beginTransaction();

            // Create or update user details
            $this->user->details()->updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'first_name' => $this->firstName,
                    'middle_name' => $this->middleName ?: null,
                    'last_name' => $this->lastName,
                    'suffix' => $this->suffix ?: null,
                    'phone_number' => $this->phoneNumber ?: null,
                ]
            );

            DB::commit();

            // Refresh user data
            $this->user->refresh();
            $this->user->load('details');

            // Update display name and sync local properties
            $this->displayName = $this->user->details?->full_name ?? $this->user->email;
            $this->loadProfileDetails();

            $this->isEditingProfile = false;
            $this->dispatch('toast', type: 'success', message: 'Profile updated successfully.');

            Log::info('Security user profile updated', [
                'user_id' => $this->user->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', type: 'error', message: 'Failed to update profile. Please try again.');
            Log::error('Profile update failed', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage()
            ]);
        } finally {
            $this->updatingProfile = false;
        }
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
