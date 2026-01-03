<?php

namespace App\Livewire\Admin\Admins;

use App\Services\Admin\Admins\AdminReadService;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminStatCards extends Component
{
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
        return app(AdminReadService::class);
    }

    public function render()
    {
        return view("livewire.admin.admins.admin-stat-cards");
    }
}
