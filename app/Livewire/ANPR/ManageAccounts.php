<?php

namespace App\Livewire\ANPR;

use App\Models\User;
use App\Models\UserDetails;
use App\Services\UserInvitationService;
use App\Traits\HasRoleBasedAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Manage Security Accounts - Only accessible by security_admin role.
 */
#[Layout('layouts.anpr-layout')]
class ManageAccounts extends Component
{
    use WithPagination;
    use HasRoleBasedAccess;

    // Search and filters
    public string $search = '';
    public string $statusFilter = 'all';

    // Modal states
    public bool $showAddModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteConfirmation = false;

    // Selected user for editing/deleting
    public ?string $selectedUserId = null;

    // Add form fields
    #[Validate('required|string|max:255')]
    public string $first_name = '';

    #[Validate('nullable|string|max:255')]
    public string $middle_name = '';

    #[Validate('required|string|max:255')]
    public string $last_name = '';

    #[Validate('nullable|string|max:10')]
    public string $suffix = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $clsu_id = '';

    #[Validate('nullable|string|max:20')]
    public string $phone_number = '';

    #[Validate('boolean')]
    public bool $is_active = true;

    #[Validate('boolean')]
    public bool $sendInvitation = true;

    protected UserInvitationService $invitationService;

    public function boot(UserInvitationService $invitationService): void
    {
        $this->invitationService = $invitationService;
    }

    public function mount(): void
    {
        // Ensure only security_admin can access this page
        if (!$this->isSecurityAdmin()) {
            abort(403, 'Only Security Admins can manage security accounts.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Get the list of security users
     */
    public function getSecurityUsersProperty()
    {
        $query = User::with(['details', 'roles'])
            ->whereHas('roles', fn($q) => $q->where('name', 'security'));

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', "%{$this->search}%")
                    ->orWhereHas('details', function ($detailsQuery) {
                        $detailsQuery->where('first_name', 'like', "%{$this->search}%")
                            ->orWhere('last_name', 'like', "%{$this->search}%")
                            ->orWhere('clsu_id', 'like', "%{$this->search}%");
                    });
            });
        }

        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    // ==================== ADD MODAL ====================

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->resetForm();
    }

    public function createSecurityUser(): void
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        try {
            // Create user with auto-generated password
            $user = User::create([
                'email' => $this->email,
                'password' => Hash::make(Str::random(32)),
                'is_active' => $this->is_active,
                'must_change_password' => true,
            ]);

            // Assign security role
            $user->assignRole('security');

            // Create user details
            UserDetails::create([
                'user_id' => $user->id,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name ?: null,
                'last_name' => $this->last_name,
                'suffix' => $this->suffix ?: null,
                'clsu_id' => $this->clsu_id ?: null,
                'phone_number' => $this->phone_number ?: null,
            ]);

            Log::info('Security account created by security_admin', [
                'user_id' => $user->id,
                'created_by' => Auth::id(),
            ]);

            // Send invitation email if enabled
            if ($this->sendInvitation) {
                $emailSent = $this->invitationService->sendWelcomeEmail($user);

                if ($emailSent) {
                    $this->dispatch('toast', type: 'success', message: 'Security account created and invitation email sent.');
                } else {
                    $this->dispatch('toast', type: 'warning', message: 'Security account created but failed to send invitation email.');
                }
            } else {
                $this->dispatch('toast', type: 'success', message: 'Security account created successfully.');
            }

            $this->closeAddModal();
        } catch (\Exception $e) {
            Log::error('Failed to create security account', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to create security account.');
        }
    }

    // ==================== EDIT MODAL ====================

    public function openEditModal(string $userId): void
    {
        $this->selectedUserId = $userId;
        $this->loadUserData();
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedUserId = null;
        $this->resetForm();
    }

    public function loadUserData(): void
    {
        if (!$this->selectedUserId) {
            return;
        }

        $user = User::with(['details', 'roles'])->find($this->selectedUserId);

        if (!$user || !$user->hasRole('security')) {
            $this->dispatch('toast', type: 'error', message: 'Security user not found.');
            $this->closeEditModal();
            return;
        }

        $this->email = $user->email;
        $this->is_active = $user->is_active ?? true;

        if ($user->details) {
            $this->first_name = $user->details->first_name ?? '';
            $this->middle_name = $user->details->middle_name ?? '';
            $this->last_name = $user->details->last_name ?? '';
            $this->suffix = $user->details->suffix ?? '';
            $this->clsu_id = $user->details->clsu_id ?? '';
            $this->phone_number = $user->details->phone_number ?? '';
        }
    }

    public function updateSecurityUser(): void
    {
        if (!$this->selectedUserId) {
            return;
        }

        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->selectedUserId,
        ]);

        try {
            $user = User::with(['details'])->find($this->selectedUserId);

            if (!$user || !$user->hasRole('security')) {
                $this->dispatch('toast', type: 'error', message: 'Security user not found.');
                return;
            }

            // Update user
            $user->update([
                'email' => $this->email,
                'is_active' => $this->is_active,
            ]);

            // Update or create user details
            $user->details()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name ?: null,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix ?: null,
                    'clsu_id' => $this->clsu_id ?: null,
                    'phone_number' => $this->phone_number ?: null,
                ]
            );

            Log::info('Security account updated by security_admin', [
                'user_id' => $user->id,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('toast', type: 'success', message: 'Security account updated successfully.');
            $this->closeEditModal();
        } catch (\Exception $e) {
            Log::error('Failed to update security account', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to update security account.');
        }
    }

    // ==================== DELETE ====================

    public function confirmDelete(string $userId): void
    {
        $this->selectedUserId = $userId;
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteConfirmation = false;
        $this->selectedUserId = null;
    }

    public function deleteSecurityUser(): void
    {
        if (!$this->selectedUserId) {
            return;
        }

        try {
            $user = User::find($this->selectedUserId);

            if (!$user || !$user->hasRole('security')) {
                $this->dispatch('toast', type: 'error', message: 'Security user not found.');
                return;
            }

            // Don't allow deleting own account
            if ($user->id === Auth::id()) {
                $this->dispatch('toast', type: 'error', message: 'You cannot delete your own account.');
                return;
            }

            $userName = $user->details?->first_name ?? 'User';
            $user->delete();

            Log::info('Security account deleted by security_admin', [
                'deleted_user_id' => $this->selectedUserId,
                'deleted_by' => Auth::id(),
            ]);

            $this->dispatch('toast', type: 'success', message: "Security account '{$userName}' deleted successfully.");
            $this->cancelDelete();
        } catch (\Exception $e) {
            Log::error('Failed to delete security account', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to delete security account.');
        }
    }

    // ==================== TOGGLE STATUS ====================

    public function toggleStatus(string $userId): void
    {
        try {
            $user = User::find($userId);

            if (!$user || !$user->hasRole('security')) {
                $this->dispatch('toast', type: 'error', message: 'Security user not found.');
                return;
            }

            // Don't allow deactivating own account
            if ($user->id === Auth::id()) {
                $this->dispatch('toast', type: 'error', message: 'You cannot deactivate your own account.');
                return;
            }

            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'activated' : 'deactivated';
            $this->dispatch('toast', type: 'success', message: "Account {$status} successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to toggle security account status', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to update account status.');
        }
    }

    // ==================== RESET PASSWORD ====================

    public function resetPassword(string $userId): void
    {
        try {
            $user = User::find($userId);

            if (!$user || !$user->hasRole('security')) {
                $this->dispatch('toast', type: 'error', message: 'Security user not found.');
                return;
            }

            // Generate new random password and set must_change_password
            $user->update([
                'password' => Hash::make(Str::random(32)),
                'must_change_password' => true,
            ]);

            // Send password reset email
            $emailSent = $this->invitationService->sendWelcomeEmail($user);

            if ($emailSent) {
                $this->dispatch('toast', type: 'success', message: 'Password reset email sent successfully.');
            } else {
                $this->dispatch('toast', type: 'warning', message: 'Password reset but failed to send email.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to reset security account password', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to reset password.');
        }
    }

    // ==================== HELPERS ====================

    public function resetForm(): void
    {
        $this->reset([
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'email',
            'clsu_id',
            'phone_number',
        ]);
        $this->is_active = true;
        $this->sendInvitation = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.anpr.manage-accounts', [
            'securityUsers' => $this->securityUsers,
        ]);
    }
}
