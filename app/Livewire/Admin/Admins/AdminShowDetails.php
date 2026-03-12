<?php

namespace App\Livewire\Admin\Admins;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminShowDetails extends Component
{
    public string $adminId;
    public bool $canEdit = false;

    public function mount(string $adminId): void
    {
        $this->adminId = $adminId;
        $this->canEdit = auth()->user()->hasAnyRole(['super_admin', 'admin_editor']);
    }

    #[On('adminUpdated')]
    #[On('fetchCardData')]
    public function refreshData(): void
    {
        // Livewire will re-render with fresh data
    }

    public function render()
    {
        $admin = User::with(['details', 'roles'])->find($this->adminId);
        $currentRole = $admin?->roles->first()?->name ?? 'admin_viewer';
        $roleBadge = ucwords(str_replace('_', ' ', $currentRole));

        return view('livewire.admin.admins.admin-show-details', [
            'admin' => $admin,
            'currentRole' => $currentRole,
            'roleBadge' => $roleBadge,
        ]);
    }
}
