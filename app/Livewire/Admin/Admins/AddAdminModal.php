<?php

namespace App\Livewire\Admin\Admins;

use App\Models\User;
use App\Models\UserDetails;
use App\Services\UserInvitationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class AddAdminModal extends Component
{
    public bool $showModal = false;

    // Form fields
    #[Validate('required|string|max:255')]
    public string $first_name = '';

    #[Validate('nullable|string|max:255')]
    public string $middle_name = '';

    #[Validate('required|string|max:255')]
    public string $last_name = '';

    #[Validate('nullable|string|max:10')]
    public string $suffix = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('nullable|string|max:50')]
    public string $clsu_id = '';

    #[Validate('nullable|string|max:20')]
    public string $phone_number = '';

    #[Validate('required|string')]
    public string $role = 'admin_viewer';

    // Option to send invitation email
    #[Validate('boolean')]
    public bool $sendInvitation = true;

    public array $availableRoles = [];

    protected UserInvitationService $invitationService;

    public function boot(UserInvitationService $invitationService): void
    {
        $this->invitationService = $invitationService;
    }

    public function mount(): void
    {
        $this->loadRoles();
    }

    public function loadRoles(): void
    {
        // Get admin roles (exclude 'applicant' role)
        $this->availableRoles = Role::whereNotIn('name', ['applicant'])
            ->pluck('name')
            ->map(fn($role) => [
                'value' => $role,
                'label' => $this->formatRoleName($role),
            ])
            ->toArray();
    }

    private function formatRoleName(string $role): string
    {
        return ucwords(str_replace('_', ' ', $role));
    }

    #[On('openAddAdminModal')]
    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

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
        $this->role = 'admin_viewer';
        $this->sendInvitation = true;
        $this->resetValidation();
    }

    public function createAdmin(): void
    {
        $this->validate();

        try {
            // Create user with auto-generated password
            $user = User::create([
                'email' => $this->email,
                'password' => Str::random(32), // Secure random password
                'is_active' => true,
                'must_change_password' => true,
            ]);

            // Assign role
            $user->assignRole($this->role);

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

            Log::info('Admin account created', [
                'user_id' => $user->id,
                'role' => $this->role,
                'created_by' => auth()->id(),
            ]);

            // Send invitation email if enabled
            if ($this->sendInvitation) {
                $emailSent = $this->invitationService->sendWelcomeEmail($user);

                if ($emailSent) {
                    $this->dispatch('toast', type: 'success', message: 'Admin account created and invitation email sent successfully.');
                } else {
                    $this->dispatch('toast', type: 'warning', message: 'Admin account created but failed to send invitation email. You can resend it later.');
                }
            } else {
                $this->dispatch('toast', type: 'success', message: 'Admin account created successfully (no invitation email sent).');
            }

            $this->dispatch('adminCreated');
            $this->dispatch('fetchCardData'); // Refresh stat cards
            $this->dispatch('filterTableData', filters: []); // Refresh table

            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Failed to create admin', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to create admin account.');
        }
    }

    public function render()
    {
        return view('livewire.admin.admins.add-admin-modal');
    }
}
