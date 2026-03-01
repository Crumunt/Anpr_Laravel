<?php

namespace App\Livewire\Admin\Applicant;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Handles account management actions for applicants from admin perspective.
 * This is a hidden component that listens for account-action events.
 */
class ApplicantAccountManager extends Component
{
    public ?string $userId = null;
    public ?User $user = null;
    public bool $processing = false;

    /**
     * Mount with optional user ID
     */
    public function mount(?string $userId = null): void
    {
        if ($userId) {
            $this->userId = $userId;
            $this->user = User::find($userId);
        }
    }

    /**
     * Handle account actions dispatched from profile-header or other components
     */
    #[On('account-action')]
    public function handleAccountAction(string $action, string $userId, string $userName = ''): void
    {
        $this->userId = $userId;
        $this->user = User::find($userId);

        if (!$this->user) {
            $this->dispatch('toast', type: 'error', message: 'User not found.');
            return;
        }

        match ($action) {
            'deactivate' => $this->deactivateAccount(),
            'activate' => $this->activateAccount(),
            'delete' => $this->deleteAccount(),
            'force-password-change' => $this->forcePasswordChange(),
            'reset-password' => $this->sendPasswordReset(),
            default => $this->dispatch('toast', type: 'error', message: 'Unknown action.'),
        };
    }

    /**
     * Deactivate the applicant's account
     */
    public function deactivateAccount(): void
    {
        if (!$this->user) return;

        $this->processing = true;

        try {
            $this->user->update(['is_active' => false]);

            // Log activity
            ActivityLogService::logAccountDeactivated($this->user, auth()->user());

            $this->dispatch('toast', type: 'success', message: 'Account has been deactivated successfully.');
            $this->dispatch('applicant-status-changed', userId: $this->userId, isActive: false);

            Log::info('Applicant account deactivated by admin', [
                'applicant_id' => $this->userId,
                'admin_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to deactivate account.');
            Log::error('Account deactivation failed', ['user_id' => $this->userId, 'error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Activate the applicant's account
     */
    public function activateAccount(): void
    {
        if (!$this->user) return;

        $this->processing = true;

        try {
            $this->user->update(['is_active' => true]);

            // Log activity
            ActivityLogService::logAccountActivated($this->user, auth()->user());

            $this->dispatch('toast', type: 'success', message: 'Account has been activated successfully.');
            $this->dispatch('applicant-status-changed', userId: $this->userId, isActive: true);

            Log::info('Applicant account activated by admin', [
                'applicant_id' => $this->userId,
                'admin_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to activate account.');
            Log::error('Account activation failed', ['user_id' => $this->userId, 'error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Delete the applicant's account
     */
    public function deleteAccount(): void
    {
        if (!$this->user) return;

        $this->processing = true;

        try {
            $userName = $this->user->details?->full_name ?? $this->user->email;

            // Delete related data first
            $this->user->details()->delete();
            $this->user->applications()->delete();
            $this->user->delete();

            $this->dispatch('toast', type: 'success', message: 'Account has been permanently deleted.');

            Log::info('Applicant account deleted by admin', [
                'applicant_id' => $this->userId,
                'applicant_name' => $userName,
                'admin_id' => auth()->id(),
            ]);

            // Redirect to applicants list after deletion
            $this->redirect(route('admin.applicants.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to delete account.');
            Log::error('Account deletion failed', ['user_id' => $this->userId, 'error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Force the applicant to change password on next login
     */
    public function forcePasswordChange(): void
    {
        if (!$this->user) return;

        $this->processing = true;

        try {
            $this->user->update(['must_change_password' => true]);

            $this->dispatch('toast', type: 'success', message: 'User will be required to change password on next login.');

            Log::info('Force password change set by admin', [
                'applicant_id' => $this->userId,
                'admin_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to set password change requirement.');
        } finally {
            $this->processing = false;
        }
    }

    /**
     * Send password reset link to the applicant
     */
    public function sendPasswordReset(): void
    {
        if (!$this->user) return;

        $this->processing = true;

        try {
            $status = Password::sendResetLink(['email' => $this->user->email]);

            if ($status === Password::RESET_LINK_SENT) {
                $this->dispatch('toast', type: 'success', message: 'Password reset link has been sent.');
                Log::info('Password reset sent by admin', [
                    'applicant_id' => $this->userId,
                    'admin_id' => auth()->id(),
                ]);
            } else {
                $this->dispatch('toast', type: 'error', message: 'Failed to send password reset link.');
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to send password reset link.');
            Log::error('Password reset failed', ['user_id' => $this->userId, 'error' => $e->getMessage()]);
        } finally {
            $this->processing = false;
        }
    }

    public function render()
    {
        // This is a hidden component, minimal view
        return <<<'HTML'
        <div class="hidden"></div>
        HTML;
    }
}
