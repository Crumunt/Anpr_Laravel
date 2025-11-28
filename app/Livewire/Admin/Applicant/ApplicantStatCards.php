<?php

namespace App\Livewire\Admin\Applicant;

use App\Services\ApplicantService;
use Livewire\Component;

class ApplicantStatCards extends Component
{
    private $applicantService;
    public $increment = 0;
    public $dashboardData = [];

    public function boot(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }

    public function mount()
    {
        $this->dashboardData = $this->applicantService->getDashboardCounts();
    }

    public function render()
    {
        return view("livewire.admin.applicant.applicant-stat-cards");
    }
}
