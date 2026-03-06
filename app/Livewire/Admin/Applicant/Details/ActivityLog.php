<?php

namespace App\Livewire\Admin\Applicant\Details;

use App\Models\User;
use App\Services\ActivityLogService;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Activity Log component that displays user activities and refreshes reactively.
 * Listens for various events to update the activity log in real-time.
 */
class ActivityLog extends Component
{
    public string $userId;
    public int $limit = 10;
    public array $activities = [];

    public function mount(string $userId, int $limit = 10): void
    {
        $this->userId = $userId;
        $this->limit = $limit;
        $this->loadActivities();
    }

    /**
     * Load activities from the database
     */
    public function loadActivities(): void
    {
        $user = User::find($this->userId);

        if ($user) {
            $this->activities = ActivityLogService::getFormattedActivities($user, $this->limit);
        } else {
            $this->activities = [];
        }
    }

    /**
     * Refresh activities when application is approved/rejected
     */
    #[On('refreshActivityLog')]
    public function refreshActivityLog(): void
    {
        $this->loadActivities();
    }

    /**
     * Refresh activities when applicant status changes
     */
    #[On('applicant-status-changed')]
    public function handleStatusChange(): void
    {
        $this->loadActivities();
    }

    /**
     * Refresh activities when documents are verified
     */
    #[On('document-verified')]
    public function handleDocumentVerified(): void
    {
        $this->loadActivities();
    }

    /**
     * Refresh activities when gate pass is assigned
     */
    #[On('gate-pass-assigned')]
    public function handleGatePassAssigned(): void
    {
        $this->loadActivities();
    }

    /**
     * Generic refresh handler for any activity update
     */
    #[On('activity-logged')]
    public function handleActivityLogged(): void
    {
        $this->loadActivities();
    }

    public function render()
    {
        return view('livewire.admin.applicant.details.activity-log');
    }
}
