<?php

namespace App\Livewire\Admin\Applicant;

use App\Services\Admin\Applicants\ApplicantReadService;
use App\Services\ApplicantService;
use Livewire\Attributes\On;
use Livewire\Component;

class ApplicantStatCards extends Component
{
    public $increment = 0;
    public $dashboardData = [];

    public function mount()
    {
        $this->fetchCardData();
    }

    #[On("fetchCardData")]
    public function fetchCardData()
    {
        $service = $this->fetchReadService();

        $this->dashboardData = $service->getDashboardCounts();
    }

    private function fetchReadService()
    {
        return app(ApplicantReadService::class);
    }

    public function render()
    {
        return view("livewire.admin.applicant.applicant-stat-cards");
    }
}
