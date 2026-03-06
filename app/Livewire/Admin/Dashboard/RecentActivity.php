<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\User;
use App\Models\Application;
use App\Models\Vehicle\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class RecentActivity extends Component
{
    public array $recentApplicants = [];
    public array $recentApplications = [];
    public array $recentVehicles = [];

    public function mount(): void
    {
        $this->loadRecentActivity();
    }

    #[On('refreshDashboard')]
    public function loadRecentActivity(): void
    {
        $this->recentApplicants = $this->getRecentApplicants();
        $this->recentApplications = $this->getRecentApplications();
        $this->recentVehicles = $this->getRecentVehicles();
    }

    private function getRecentApplicants(): array
    {
        return User::with(['details', 'roles'])
            ->whereHas('roles', fn($q) => $q->where('name', 'applicant'))
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->details?->full_name ?? $user->email,
                'email' => $user->email,
                'created_at' => $user->created_at->diffForHumans(),
                'avatar_initials' => strtoupper(substr($user->details?->first_name ?? $user->email, 0, 1) . substr($user->details?->last_name ?? '', 0, 1)),
            ])
            ->toArray();
    }

    private function getRecentApplications(): array
    {
        return Application::with(['user.details', 'status', 'applicantTypeModel'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($app) => [
                'id' => $app->id,
                'applicant_name' => $app->user?->details?->full_name ?? $app->user?->email ?? 'Unknown',
                'applicant_type' => $app->applicantTypeModel?->label ?? 'Unknown',
                'status' => $app->status?->code ?? 'pending',
                'status_label' => Str::headline($app->status?->code ?? 'Pending'),
                'created_at' => $app->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    private function getRecentVehicles(): array
    {
        return Vehicle::with(['user.details', 'status'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($vehicle) => [
                'id' => $vehicle->id,
                'plate_number' => $vehicle->plate_number,
                'owner_name' => $vehicle->user?->details?->full_name ?? 'Unknown',
                'make_model' => trim(($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')),
                'status' => $vehicle->status?->code ?? 'pending',
                'status_label' => Str::headline($vehicle->status?->code ?? 'Pending'),
                'created_at' => $vehicle->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.recent-activity');
    }
}
