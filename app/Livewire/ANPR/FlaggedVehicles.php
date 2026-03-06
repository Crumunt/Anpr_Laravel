<?php

namespace App\Livewire\ANPR;

use App\Models\ANPR\FlaggedVehicle;
use App\Models\ANPR\Record;
use App\Models\Vehicle\Vehicle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

/**
 * Flagged Vehicles Component
 *
 * Comprehensive management of flagged vehicle records with filtering,
 * bulk actions, and detailed view capabilities.
 */
class FlaggedVehicles extends Component
{
    use WithPagination;

    /**
     * Search query for plate number
     */
    #[Url(as: 'search')]
    public string $search = '';

    /**
     * Priority filter (all, high, medium, low)
     */
    #[Url(as: 'priority')]
    public string $priorityFilter = 'all';

    /**
     * Status filter (all, active, resolved, dismissed)
     */
    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    /**
     * Date range filter
     */
    public string $dateRange = 'all';

    /**
     * Sort field
     */
    public string $sortField = 'created_at';

    /**
     * Sort direction
     */
    public string $sortDirection = 'desc';

    /**
     * Items per page
     */
    public int $perPage = 15;

    /**
     * Selected record IDs for bulk actions
     */
    public array $selectedRecords = [];

    /**
     * Select all flag
     */
    public bool $selectAll = false;

    /**
     * Show detail modal
     */
    public bool $showDetailModal = false;

    /**
     * Currently viewing flagged vehicle
     */
    public ?FlaggedVehicle $viewingRecord = null;

    /**
     * Show flag modal for manual flagging
     */
    public bool $showFlagModal = false;

    /**
     * Manual flag form data
     */
    public array $flagForm = [
        'plate_number' => '',
        'reason' => '',
        'priority' => 'medium',
        'notes' => '',
        'vehicle_make' => '',
        'vehicle_model' => '',
        'vehicle_color' => '',
        'vehicle_type' => '',
    ];

    /**
     * Statistics
     */
    public array $stats = [
        'total_flagged' => 0,
        'high_priority' => 0,
        'medium_priority' => 0,
        'resolved_today' => 0,
    ];

    /**
     * Available reason options
     */
    public array $reasonOptions = [];

    /**
     * Initialize the component
     */
    public function mount(): void
    {
        $this->perPage = config('anpr.dashboard.records_per_page', 15);
        $this->reasonOptions = FlaggedVehicle::REASON_LABELS;
        $this->loadStats();
    }

    /**
     * Load statistics
     */
    public function loadStats(): void
    {
        $this->stats = [
            'total_flagged' => FlaggedVehicle::count(),
            'high_priority' => FlaggedVehicle::where('priority', 'high')->count(),
            'medium_priority' => FlaggedVehicle::where('priority', 'medium')->count(),
            'resolved_today' => FlaggedVehicle::resolvedToday()->count(),
        ];
    }

    /**
     * Get flagged vehicles query
     */
    protected function getFlaggedVehiclesQuery()
    {
        return FlaggedVehicle::query()
            ->withinDateRange($this->dateRange)
            ->when($this->search, fn($q) => $q->searchPlate($this->search))
            ->when($this->priorityFilter !== 'all', fn($q) => $q->byPriority($this->priorityFilter))
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * Get paginated flagged vehicles
     */
    public function getRecordsProperty()
    {
        return $this->getFlaggedVehiclesQuery()->paginate($this->perPage);
    }

    /**
     * Reset pagination on filter change
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    public function updatedDateRange(): void
    {
        $this->resetPage();
        $this->loadStats();
    }

    /**
     * Sort by field
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    /**
     * Toggle select all
     */
    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedRecords = $this->records->pluck('id')->toArray();
        } else {
            $this->selectedRecords = [];
        }
    }

    /**
     * Toggle single record selection
     */
    public function toggleSelection(string $recordId): void
    {
        if (in_array($recordId, $this->selectedRecords)) {
            $this->selectedRecords = array_diff($this->selectedRecords, [$recordId]);
            $this->selectAll = false;
        } else {
            $this->selectedRecords[] = $recordId;
        }
    }

    /**
     * Resolve a single flagged vehicle
     */
    public function resolveRecord(string $recordId): void
    {
        $record = FlaggedVehicle::find($recordId);

        if ($record && $record->status === 'active') {
            $record->resolve(Auth::user());
            $this->loadStats();

            $this->dispatch('record-resolved', [
                'message' => 'Vehicle flag resolved successfully',
            ]);
        }
    }

    /**
     * Dismiss a single flagged vehicle
     */
    public function dismissRecord(string $recordId): void
    {
        $record = FlaggedVehicle::find($recordId);

        if ($record && $record->status === 'active') {
            $record->dismiss(Auth::user());
            $this->loadStats();

            $this->dispatch('record-dismissed', [
                'message' => 'Vehicle flag dismissed successfully',
            ]);
        }
    }

    /**
     * Bulk resolve selected records
     */
    public function bulkResolve(): void
    {
        $count = 0;
        $user = Auth::user();

        foreach ($this->selectedRecords as $recordId) {
            $record = FlaggedVehicle::find($recordId);
            if ($record && $record->status === 'active') {
                $record->resolve($user);
                $count++;
            }
        }

        $this->selectedRecords = [];
        $this->selectAll = false;
        $this->loadStats();

        $this->dispatch('bulk-resolve-complete', [
            'message' => "{$count} vehicle flag(s) resolved successfully",
        ]);
    }

    /**
     * View record details
     */
    public function viewDetails(string $recordId): void
    {
        $this->viewingRecord = FlaggedVehicle::with('record')->find($recordId);
        $this->showDetailModal = true;
    }

    /**
     * Close detail modal
     */
    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->viewingRecord = null;
    }

    /**
     * Open flag modal for manual flagging
     */
    public function openFlagModal(): void
    {
        $this->flagForm = [
            'plate_number' => '',
            'reason' => '',
            'priority' => 'medium',
            'notes' => '',
            'vehicle_make' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'vehicle_type' => '',
        ];
        $this->showFlagModal = true;
    }

    /**
     * Close flag modal
     */
    public function closeFlagModal(): void
    {
        $this->showFlagModal = false;
        $this->resetValidation();
    }

    /**
     * Flag a vehicle manually
     */
    public function flagVehicle(): void
    {
        $this->validate([
            'flagForm.plate_number' => 'required|string|max:20',
            'flagForm.reason' => 'required|string|max:50',
            'flagForm.priority' => 'required|in:high,medium,low',
            'flagForm.notes' => 'nullable|string|max:1000',
            'flagForm.vehicle_make' => 'nullable|string|max:50',
            'flagForm.vehicle_model' => 'nullable|string|max:50',
            'flagForm.vehicle_color' => 'nullable|string|max:30',
            'flagForm.vehicle_type' => 'nullable|string|max:30',
        ]);

        $user = Auth::user();
        $plateNumber = strtoupper(trim($this->flagForm['plate_number']));

        // Check if this plate is already flagged and active
        $existingFlag = FlaggedVehicle::where('plate_number', $plateNumber)
            ->where('status', 'active')
            ->first();

        if ($existingFlag) {
            $this->addError('flagForm.plate_number', 'This plate number is already flagged.');
            return;
        }

        // Try to find vehicle details from existing records or vehicles
        $vehicle = Vehicle::where('plate_number', 'like', "%{$plateNumber}%")->first();
        $record = Record::where('plate_number', 'like', "%{$plateNumber}%")
            ->orderBy('created_at', 'desc')
            ->first();

        // Create the flagged vehicle entry
        $flaggedVehicle = FlaggedVehicle::create([
            'plate_number' => $plateNumber,
            'reason' => $this->flagForm['reason'],
            'reason_label' => FlaggedVehicle::REASON_LABELS[$this->flagForm['reason']] ?? ucfirst($this->flagForm['reason']),
            'priority' => $this->flagForm['priority'],
            'notes' => $this->flagForm['notes'] ?: null,
            'status' => 'active',
            'vehicle_make' => $this->flagForm['vehicle_make'] ?: ($vehicle?->make ?? null),
            'vehicle_model' => $this->flagForm['vehicle_model'] ?: ($vehicle?->model ?? null),
            'vehicle_color' => $this->flagForm['vehicle_color'] ?: ($vehicle?->color ?? null),
            'vehicle_type' => $this->flagForm['vehicle_type'] ?: ($vehicle?->type ?? null),
            'record_id' => $record?->id,
            'flagged_by_id' => $user?->id,
            'flagged_by_name' => $user?->name ?? 'System',
            'flagged_by_role' => $user?->roles->first()?->name ?? 'Security Officer',
        ]);

        $this->closeFlagModal();
        $this->loadStats();

        $this->dispatch('vehicle-flagged', [
            'message' => "Vehicle {$plateNumber} has been flagged successfully",
        ]);
    }

    /**
     * Clear all filters
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->priorityFilter = 'all';
        $this->statusFilter = 'all';
        $this->dateRange = 'all';
        $this->resetPage();
        $this->loadStats();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.anpr.flagged-vehicles', [
            'records' => $this->records,
            'reasonOptions' => $this->reasonOptions,
        ]);
    }
}
