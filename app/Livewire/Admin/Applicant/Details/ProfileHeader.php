<?php

namespace App\Livewire\Admin\Applicant\Details;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Profile Header component that displays user info and account actions.
 * Listens for status changes to update reactively.
 */
class ProfileHeader extends Component
{
    // User properties
    public ?string $userId = null;
    public string $title = 'John Doe';
    public ?string $subtitle = null;
    public string $initials = 'JD';
    public ?string $avatar = null;
    public string $status = 'Active';
    public string $statusClass = 'bg-green-100 text-green-800';
    public ?string $statusIcon = null;
    public string $user_id = 'APP-2023-001'; // Display ID (CLSU ID)
    public bool $isActive = true;
    public ?string $lastActive = null;
    public array $tags = [];
    public bool $verified = false;
    public ?string $email = null;

    /**
     * Mount the component with user data
     */
    public function mount(
        ?string $userId = null,
        string $title = 'John Doe',
        ?string $subtitle = null,
        string $initials = 'JD',
        ?string $avatar = null,
        string $status = 'Active',
        string $statusClass = 'bg-green-100 text-green-800',
        ?string $statusIcon = null,
        string $user_id = 'APP-2023-001',
        bool $isActive = true,
        ?string $lastActive = null,
        array $tags = [],
        bool $verified = false,
        ?string $email = null
    ): void {
        $this->userId = $userId;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->initials = $initials;
        $this->avatar = $avatar;
        $this->status = $status;
        $this->statusClass = $statusClass;
        $this->statusIcon = $statusIcon;
        $this->user_id = $user_id;
        $this->isActive = $isActive;
        $this->lastActive = $lastActive;
        $this->tags = $tags;
        $this->verified = $verified;
        $this->email = $email;
    }

    /**
     * Listen for account status changes
     */
    #[On('applicant-status-changed')]
    public function handleStatusChange(string $userId, bool $isActive): void
    {
        // Only update if this is for the same user
        if ($this->userId !== $userId) {
            return;
        }

        $this->isActive = $isActive;
        $this->status = $isActive ? 'Active' : 'Inactive';
        $this->statusClass = $isActive
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    /**
     * Dispatch account action events
     */
    public function dispatchAccountAction(string $action): void
    {
        $this->dispatch('account-action',
            action: $action,
            userId: $this->userId,
            userName: $this->title
        );
    }

    public function render()
    {
        return view('livewire.admin.applicant.details.profile-header');
    }
}
