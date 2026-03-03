<?php

namespace App\Livewire\Admin\Applicant;

use App\Services\Admin\Applicants\ApplicantReadService;
use Livewire\Attributes\On;
use Livewire\Component;

class ArchivedCount extends Component
{
    public int $count = 0;

    protected ApplicantReadService $applicantReadService;

    public function boot(ApplicantReadService $applicantReadService): void
    {
        $this->applicantReadService = $applicantReadService;
    }

    public function mount(): void
    {
        $this->loadCount();
    }

    #[On('fetchCardData')]
    #[On('refetchTableData')]
    public function loadCount(): void
    {
        $this->count = $this->applicantReadService->getArchivedCount();
    }

    public function render()
    {
        return view('livewire.admin.applicant.archived-count');
    }
}
