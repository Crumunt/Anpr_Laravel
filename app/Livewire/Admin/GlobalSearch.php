<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Vehicle\Vehicle;
use Livewire\Component;
use Livewire\Attributes\On;

class GlobalSearch extends Component
{
    public string $query = '';
    public array $results = [];
    public bool $showDropdown = false;
    public bool $isLoading = false;
    public int $activeIndex = -1;

    public function updatedQuery($value)
    {
        $this->query = trim($value);
        $this->activeIndex = -1;

        if (strlen($this->query) < 1) {
            $this->results = [];
            $this->showDropdown = false;
            return;
        }

        $this->search();
    }

    public function search()
    {
        if (empty($this->query)) {
            $this->results = [];
            $this->showDropdown = false;
            return;
        }

        $this->isLoading = true;
        $q = $this->query;

        // Search users/applicants - names are in the details relationship
        $users = User::query()
            ->with('details')
            ->whereHas('details', function ($qb) use ($q) {
                $qb->where('first_name', 'like', "{$q}%")
                    ->orWhere('last_name', 'like', "{$q}%")
                    ->orWhere('middle_name', 'like', "{$q}%")
                    ->orWhere('clsu_id', 'like', "{$q}%");
            })
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(function (User $u) {
                $details = $u->details;
                $fullName = $details
                    ? $details->full_name
                    : $u->email;
                $clsuId = $details?->clsu_id;

                return [
                    'type' => 'Applicant',
                    'id' => $u->id,
                    'label' => $fullName,
                    'sublabel' => $clsuId ? "ID: {$clsuId} • {$u->email}" : $u->email,
                    'url' => route('admin.applicant.show', $u->id),
                ];
            });

        // Search vehicles - include gate pass search
        $vehicles = Vehicle::query()
            ->with(['user.details'])
            ->where(function ($qb) use ($q) {
                $qb->where('plate_number', 'like', "%{$q}%")
                    ->orWhere('make', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%")
                    ->orWhere('assigned_gate_pass', 'like', "%{$q}%");
            })
            ->limit(5)
            ->get()
            ->map(function (Vehicle $v) {
                $vehicleInfo = $v->vehicle_info ?: trim(($v->make ?? '') . ' ' . ($v->model ?? ''));
                $ownerName = $v->user?->details?->full_name ?? 'Unknown Owner';
                $ownerId = $v->user?->id;
                $gatePass = $v->assigned_gate_pass;

                $sublabel = $v->plate_number ?? 'No plate';
                if ($gatePass) {
                    $sublabel .= " • Gate Pass: {$gatePass}";
                }
                $sublabel .= " • {$ownerName}";

                // Redirect to the vehicle owner's applicant page
                $url = $ownerId
                    ? route('admin.applicant.show', $ownerId)
                    : route('admin.applicant');

                return [
                    'type' => 'Vehicle',
                    'id' => $v->id,
                    'label' => $vehicleInfo ?: 'Unknown Vehicle',
                    'sublabel' => $sublabel,
                    'gatePass' => $gatePass,
                    'url' => $url,
                ];
            });

        // Combine results using concat to avoid getKey() issues with arrays
        $this->results = $users->concat($vehicles)->take(10)->values()->toArray();
        $this->isLoading = false;
        $this->showDropdown = true;
    }

    public function clearSearch()
    {
        $this->query = '';
        $this->results = [];
        $this->showDropdown = false;
        $this->activeIndex = -1;
    }

    public function hideDropdown()
    {
        $this->showDropdown = false;
        $this->activeIndex = -1;
    }

    public function navigateUp()
    {
        if (count($this->results) === 0) return;

        $this->activeIndex = $this->activeIndex <= 0
            ? count($this->results) - 1
            : $this->activeIndex - 1;
    }

    public function navigateDown()
    {
        if (count($this->results) === 0) return;

        $this->activeIndex = $this->activeIndex >= count($this->results) - 1
            ? 0
            : $this->activeIndex + 1;
    }

    public function selectActive()
    {
        if ($this->activeIndex >= 0 && $this->activeIndex < count($this->results)) {
            return redirect()->to($this->results[$this->activeIndex]['url']);
        }

        // Fallback to show all results
        if (!empty($this->query)) {
            return redirect()->route('admin.search.results', ['q' => $this->query]);
        }
    }

    public function goToResults()
    {
        if (!empty($this->query)) {
            return redirect()->route('admin.search.results', ['q' => $this->query]);
        }
    }

    public function render()
    {
        return view('livewire.admin.global-search');
    }
}
