<?php

namespace App\Livewire\Admin\Admins;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditAdminModal extends Component
{
    public bool $showModal = false;
    public ?string $adminId = null;

    // Form fields
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

    #[Validate('required|string')]
    public string $role = 'admin_viewer';

    #[Validate('boolean')]
    public bool $is_active = true;

    public array $availableRoles = [];

    public function mount(): void
    {
        $this->loadRoles();
    }

    public function loadRoles(): void
    {
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

    #[On('openEditAdminModal')]
    public function openModal(string $id): void
    {
        $this->adminId = $id;
        $this->loadAdminData();
        $this->showModal = true;
    }

    public function loadAdminData(): void
    {
        if (!$this->adminId) {
            return;
        }

        $admin = User::with(['details', 'roles'])->find($this->adminId);

        if (!$admin) {
            $this->dispatch('toast', type: 'error', message: 'Admin not found.');
            $this->closeModal();
            return;
        }

        $this->first_name = $admin->details?->first_name ?? '';
        $this->middle_name = $admin->details?->middle_name ?? '';
        $this->last_name = $admin->details?->last_name ?? '';
        $this->suffix = $admin->details?->suffix ?? '';
        $this->email = $admin->email;
        $this->clsu_id = $admin->details?->clsu_id ?? '';
        $this->phone_number = $admin->details?->phone_number ?? '';
        $this->role = $admin->roles->first()?->name ?? 'admin_viewer';
        $this->is_active = $admin->is_active;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->adminId = null;
        $this->reset([
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'email',
            'clsu_id',
            'phone_number',
            'is_active',
        ]);
        $this->role = 'admin_viewer';
        $this->resetValidation();
    }

    public function updateAdmin(): void
    {
        // Add unique email validation excluding current admin
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $this->adminId,
        ]);

        $this->validate();

        try {
            $user = User::findOrFail($this->adminId);

            // Update user
            $user->update([
                'email' => $this->email,
                'is_active' => $this->is_active,
            ]);

            // Sync role
            $user->syncRoles([$this->role]);

            // Update or create user details
            if ($user->details) {
                $user->details->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name ?: null,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix ?: null,
                    'clsu_id' => $this->clsu_id ?: null,
                    'phone_number' => $this->phone_number ?: null,
                ]);
            } else {
                UserDetails::create([
                    'user_id' => $user->id,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name ?: null,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix ?: null,
                    'clsu_id' => $this->clsu_id ?: null,
                    'phone_number' => $this->phone_number ?: null,
                ]);
            }

            Log::info('Admin account updated', [
                'user_id' => $user->id,
                'role' => $this->role,
                'updated_by' => auth()->id(),
            ]);

            $this->dispatch('toast', type: 'success', message: 'Admin account updated successfully.');
            $this->dispatch('fetchCardData');
            $this->dispatch('filterTableData', filters: []);

            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Failed to update admin', ['error' => $e->getMessage()]);
            $this->dispatch('toast', type: 'error', message: 'Failed to update admin account.');
        }
    }

    public function render()
    {
        return view('livewire.admin.admins.edit-admin-modal');
    }
}
