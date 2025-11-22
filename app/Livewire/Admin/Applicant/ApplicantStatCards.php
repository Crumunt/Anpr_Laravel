<?php

namespace App\Livewire\Admin\Applicant;

use App\Services\ApplicantService;
use Livewire\Component;

class ApplicantStatCards extends Component
{
    private $applicantService;
    public $increment = 0;

    public function boot(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }

    public function count() {
        $this->increment++;
    }

    public function render()
    {
        $userType = 'applicant';
        $dashboardData = $this->applicantService->getDashboardCounts();

        return view('livewire.admin.applicant.applicant-stat-cards', compact('dashboardData', 'userType'));
    }
}
