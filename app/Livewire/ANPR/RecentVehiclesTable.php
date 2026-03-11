<?php

namespace App\Livewire\ANPR;

use App\Models\ANPR\Gate;
use App\Models\ANPR\Record;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

/**
 * Recent Vehicles Table Component
 *
 * Displays recent vehicle scan records with filtering, pagination,
 * and action buttons for editing and flagging.
 */
class RecentVehiclesTable extends Component
{
    use WithPagination;

    /**
     * Search query for plate number
     */
    #[Url(as: 'search')]
    public string $search = '';

    /**
     * Gate name filter (Main Gate, Second Gate, etc.)
     */
    #[Url(as: 'gate')]
    public string $gateFilter = 'all';

    /**
     * Gate location filter (Entry, Exit, All)
     */
    #[Url(as: 'direction')]
    public string $locationFilter = 'all';

    /**
     * Status filter (all, normal, flagged)
     */
    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    /**
     * Sort field
     */
    public string $sortField = 'detected_at';

    /**
     * Sort direction
     */
    public string $sortDirection = 'desc';

    /**
     * Items per page
     */
    public int $perPage = 15;

    /**
     * Record being edited
     */
    public ?string $editingRecordId = null;

    /**
     * Edit form data
     */
    public array $editForm = [
        'plate_number' => '',
        'gate_id' => '',
        'confidence' => 0,
    ];

    /**
     * Available gates for filtering (unique gate names)
     */
    public array $availableGates = [];

    /**
     * Available gate locations for filtering
     */
    public array $availableLocations = [];

    /**
     * All gates with their details
     */
    public array $allGates = [];

    /**
     * Show edit modal
     */
    public bool $showEditModal = false;

    /**
     * Show add log modal
     */
    public bool $showAddLogModal = false;

    /**
     * Track IDs of flagged records that have already been alerted
     */
    public array $alertedFlaggedIds = [];

    /**
     * Add log form data
     */
    public array $addLogForm = [
        'plate_number' => '',
        'gate_name' => '',
        'direction' => '',
    ];

    /**
     * Initialize the component
     */
    public function mount(): void
    {
        $this->perPage = config('anpr.dashboard.records_per_page', 15);
        $this->loadAvailableGates();
        $this->loadAvailableLocations();
    }

    /**
     * Load available gates from database
     */
    protected function loadAvailableGates(): void
    {
        // Get unique gate names from the gates table
        $this->availableGates = Gate::active()
            ->select('gate_name')
            ->distinct()
            ->orderBy('gate_name')
            ->pluck('gate_name', 'gate_name')
            ->toArray();

        // Load all gates for dropdown with full details
        $this->allGates = Gate::active()
            ->orderBy('gate_name')
            ->orderBy('gate_location')
            ->get()
            ->map(fn($gate) => [
                'id' => $gate->id,
                'gate_name' => $gate->gate_name,
                'gate_location' => $gate->gate_location,
                'slug' => $gate->slug,
                'display_name' => $gate->display_name,
            ])
            ->toArray();

        // If no gates in database yet, fall back to config
        if (empty($this->availableGates)) {
            $configGates = config('anpr.gates', []);
            foreach ($configGates as $key => $label) {
                $this->availableGates[$key] = $label;
            }
        }
    }

    /**
     * Load available gate locations
     */
    protected function loadAvailableLocations(): void
    {
        $this->availableLocations = Gate::active()
            ->select('gate_location')
            ->distinct()
            ->orderBy('gate_location')
            ->pluck('gate_location', 'gate_location')
            ->toArray();

        // If no locations in database yet, fall back to constants
        if (empty($this->availableLocations)) {
            $this->availableLocations = [
                Gate::LOCATION_ENTRY => Gate::LOCATION_ENTRY,
                Gate::LOCATION_EXIT => Gate::LOCATION_EXIT,
            ];
        }
    }

    /**
     * Reset pagination when search changes
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when gate filter changes
     */
    public function updatedGateFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when location filter changes
     */
    public function updatedLocationFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status filter changes
     */
    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by a specific field
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
     * Get the records query with all filters applied
     */
    protected function getRecordsQuery()
    {
        $hours = config('anpr.dashboard.metrics_hours', 24);

        return Record::query()
            ->with('gate')
            ->where('created_at', '>=', now()->subHours($hours))
            ->when($this->search, fn($q) => $q->searchPlate($this->search))
            ->when($this->gateFilter !== 'all', fn($q) => $q->byGateName($this->gateFilter))
            ->when($this->locationFilter !== 'all', fn($q) => $q->byGateLocation($this->locationFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * Get paginated records
     */
    public function getRecordsProperty()
    {
        $records = $this->getRecordsQuery()->paginate($this->perPage);

        // Apply status filter in PHP for simulation mode
        if ($this->statusFilter !== 'all' && config('anpr.flagging.simulation_enabled', true)) {
            $records->getCollection()->transform(function ($record) {
                return $record;
            });
        }

        return $records;
    }

    /**
     * Toggle the flagged status of a record
     */
    public function toggleFlag(string $recordId): void
    {
        $record = Record::find($recordId);

        if ($record) {
            $newStatus = $record->toggleFlag();

            $this->dispatch('record-flagged', [
                'id' => $recordId,
                'status' => $newStatus,
                'message' => $newStatus ? 'Record flagged successfully' : 'Record unflagged successfully',
            ]);
        }
    }

    /**
     * Open the edit modal for a record
     */
    public function editRecord(string $recordId): void
    {
        $record = Record::find($recordId);

        if ($record) {
            $this->editingRecordId = $recordId;
            $this->editForm = [
                'plate_number' => $record->plate_number,
                'gate_id' => $record->gate_id ?? '',
                'confidence' => $record->confidence,
            ];
            $this->showEditModal = true;
        }
    }

    /**
     * Close the edit modal
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingRecordId = null;
        $this->editForm = [
            'plate_number' => '',
            'gate_id' => '',
            'confidence' => 0,
        ];
        $this->resetValidation();
    }

    /**
     * Save the edited record
     */
    public function saveRecord(): void
    {
        $this->validate([
            'editForm.plate_number' => 'required|string|max:20',
            'editForm.gate_id' => 'nullable|integer|exists:gates,id',
            'editForm.confidence' => 'required|numeric|min:0|max:1',
        ]);

        $record = Record::find($this->editingRecordId);

        if ($record) {
            $record->update([
                'plate_number' => strtoupper($this->editForm['plate_number']),
                'gate_id' => $this->editForm['gate_id'] ?: null,
                'confidence' => $this->editForm['confidence'],
            ]);

            $this->dispatch('record-updated', [
                'id' => $this->editingRecordId,
                'message' => 'Record updated successfully',
            ]);

            $this->closeEditModal();
        }
    }

    /**
     * Open the add log modal, prefilling from current filters
     */
    public function openAddLogModal(): void
    {
        $this->addLogForm = [
            'plate_number' => '',
            'gate_name' => $this->gateFilter !== 'all' ? $this->gateFilter : '',
            'direction' => $this->locationFilter !== 'all' ? $this->locationFilter : '',
        ];
        $this->showAddLogModal = true;
    }

    /**
     * Close the add log modal
     */
    public function closeAddLogModal(): void
    {
        $this->showAddLogModal = false;
        $this->addLogForm = [
            'plate_number' => '',
            'gate_name' => '',
            'direction' => '',
        ];
        $this->resetValidation();
    }

    /**
     * Save a manual log entry
     */
    public function saveManualLog(): void
    {
        $this->validate([
            'addLogForm.plate_number' => 'required|string|max:20',
            'addLogForm.gate_name' => 'required|string',
            'addLogForm.direction' => 'required|string|in:Entry,Exit',
        ]);

        // Find the matching gate by name and location
        $gate = Gate::where('gate_name', $this->addLogForm['gate_name'])
            ->where('gate_location', $this->addLogForm['direction'])
            ->first();

        Record::create([
            'plate_number' => strtoupper($this->addLogForm['plate_number']),
            'confidence' => 1.0,
            'gate_id' => $gate?->id,
            'gate_type' => $gate?->slug,
            'location' => $this->addLogForm['direction'],
            'detected_at' => now(),
            'is_flagged' => false,
            'camera_id' => 'MANUAL',
        ]);

        $this->dispatch('record-updated', [
            'message' => 'Manual log entry added successfully',
        ]);

        $this->closeAddLogModal();
    }

    /**
     * Refresh the table data
     */
    #[On('refresh-vehicles-table')]
    public function refresh(): void
    {
        // Livewire will automatically re-render
    }

    /**
     * Clear all filters
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->gateFilter = 'all';
        $this->locationFilter = 'all';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    /**
     * Check for flagged records and show toast notification
     * This is called during Livewire polling
     */
    public function checkFlaggedRecords(): void
    {
        $hours = config('anpr.dashboard.metrics_hours', 24);

        // Get all flagged records in the current time window
        $flaggedRecords = Record::query()
            ->where('created_at', '>=', now()->subHours($hours))
            ->where('is_flagged', true)
            ->get();

        if ($flaggedRecords->isEmpty()) {
            // Reset alerted IDs when no flagged records exist
            $this->alertedFlaggedIds = [];
            return;
        }

        // Find new flagged records that haven't been alerted yet
        $newFlaggedIds = $flaggedRecords->pluck('id')->diff($this->alertedFlaggedIds)->toArray();

        if (!empty($newFlaggedIds)) {
            $newCount = count($newFlaggedIds);
            $totalCount = $flaggedRecords->count();

            // Get plate numbers for the new flagged records
            $newPlates = $flaggedRecords->whereIn('id', $newFlaggedIds)->pluck('plate_number')->take(3)->implode(', ');

            // Build the message
            if ($newCount === 1) {
                $message = "Flagged vehicle detected: {$newPlates}";
            } else {
                $message = "{$newCount} new flagged vehicles detected. Total: {$totalCount}";
            }

            // Dispatch toast notification
            $this->dispatch('toast', type: 'warning', message: $message);

            // Mark these as alerted
            $this->alertedFlaggedIds = $flaggedRecords->pluck('id')->toArray();
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        // Check for flagged records on each poll/render
        $this->checkFlaggedRecords();

        return view('livewire.anpr.recent-vehicles-table', [
            'records' => $this->records,
        ]);
    }
}
