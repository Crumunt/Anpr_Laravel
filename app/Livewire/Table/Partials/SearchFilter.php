<?php

namespace App\Livewire\Table\Partials;

use App\Models\Status;
use Livewire\Component;

class SearchFilter extends Component
{
    public $filterType;
    public $filters = [];
    public $search = "";
    public $statusFilter = [],
        $sortFilter = [],
        $roleFilter = [];

    // selected kineme
    public $selectedRole = [];
    public $selectedAllTypes = false;
    public $selectedStatus;
    public $selectedSortOption;

    // counters
    public $activeCount = 0;

    // show statuses
    public $showReset = true;
    public $showDateRange = false;
    public $showTypeFilter = true;
    public $showStatusFilter = true;
    public $showRoleFilter = false;
    public $showSortFilter = true;
    public $formAction = null;

    public function mount($filterType = "applicant")
    {
        $this->filterType = $filterType;
        $this->statusFilter = $this->fetchFilterOptions();
        $this->sortFilter = $this->fetchSortOptions();
        $this->roleFilter = $this->fetchRoleFiltersOptions();
    }

    public function resetFilters()
    {
        $this->reset("selectedStatus", "selectedRole", "selectedSortOption");
        $this->appliedFilters();
    }

    public function updatedSearch($value)
    {
        $this->appliedFilters();
    }

    public function updatedSelectedStatus($value)
    {
        $this->appliedFilters();
    }

    public function updatedSelectedSortOption($value)
    {
        $this->appliedFilters();
    }

    private function appliedFilters()
    {
        $this->filters = [
            "filter_type" => $this->filterType,
            "search" => $this->search,
            "status" => $this->selectedStatus,
            "applicant_types" => $this->selectedRole,
            "sort_by" => $this->selectedSortOption,
        ];

        $this->fetchActiveFiltersCount();

        $this->dispatch("table-filter", filters: $this->filters);
    }

    public function fetchActiveFiltersCount()
    {
        $default = [
            "status" => null,
            "applicant_types" => [],
            "sort_by" => null,
        ];

        $filtersToCount = collect($this->filters)->except(['filter_type', 'search']);

        $this->activeCount = $filtersToCount->filter(function ($value, $key) use ($default) {
            return $value !== $default[$key];
        })->count();

        $this->dispatch('log-action', $this->activeCount, $filtersToCount->filter(function ($value, $key) use ($default) {
            return $value !== $default[$key];
        }));
    }

    public function toggleRow($id)
    {
        if (in_array($id, $this->selectedRole)) {
            $this->selectedRole = array_diff($this->selectedRole, [$id]);
        } else {
            $this->selectedRole[] = $id;
        }
        $this->appliedFilters();
        $this->dispatch(
            "log-action",
            $this->selectedRole,
            array_keys($this->roleFilter),
        );
    }

    private function fetchFilterOptions()
    {
        $baseFilter = ["" => "All Statuses"];
        $applicationFilters = Status::where("type", "application")
            ->whereIn("code", ["approved", "under_review", "rejected"])
            ->pluck("status_name", "code")
            ->toArray();

        return [...$baseFilter, ...$applicationFilters];
    }

    private function fetchSortOptions()
    {
        return [
            "newest" => "Newest First",
            "oldest" => "Oldest First",
            "a-z" => "A-Z",
            "z-a" => "Z-A",
        ];
    }

    private function fetchRoleFiltersOptions()
    {
        return match ($this->filterType) {
            "applicant" => [
                "student" => "Student",
                "faculty" => "Faculty",
                "staff" => "Staff",
            ],
            "vehicle" => [
                "motorcycle" => "Motorcycle",
                "car" => "Car",
                "truck" => "Truck",
            ],
            "gatePass" => [
                "gate_pass" => "Gate Pass",
                "gate_pass_tag" => "Gate Pass Tag",
                "gate_pass_reader" => "Gate Pass Reader",
            ],
            "admins" => [
                // Added admin type options
                "super_admin" => "Super Admin",
                "admin" => "Admin",
                "moderator" => "Moderator",
            ],
            default => [],
        };
    }

    public function render()
    {
        return view("livewire.table.partials.search-filter");
    }
}
