<?php

namespace App\Livewire\ANPR;

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
     * Gate filter
     */
    #[Url(as: 'gate')]
    public string $gateFilter = 'all';

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
        'gate_type' => '',
        'confidence' => 0,
    ];

    /**
     * Available gates for filtering
     */
    public array $availableGates = [];

    /**
     * Show edit modal
     */
    public bool $showEditModal = false;

    /**
     * Initialize the component
     */
    public function mount(): void
    {
        $this->perPage = config('anpr.dashboard.records_per_page', 15);
        $this->loadAvailableGates();
    }

    /**
     * Load available gates from config and database
     */
    protected function loadAvailableGates(): void
    {
        // Start with configured gates
        $this->availableGates = config('anpr.gates', []);

        // Add any gates found in the database that aren't in config
        $dbGates = Record::select('gate_type')
            ->whereNotNull('gate_type')
            ->distinct()
            ->pluck('gate_type')
            ->toArray();

        foreach ($dbGates as $gate) {
            if (!isset($this->availableGates[$gate])) {
                $this->availableGates[$gate] = ucwords(str_replace(['-', '_'], ' ', $gate));
            }
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
            ->whereNot('created_at', '>=', now()->subHours($hours))
            ->when($this->search, fn($q) => $q->searchPlate($this->search))
            ->when($this->gateFilter !== 'all', fn($q) => $q->byGate($this->gateFilter))
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
                'gate_type' => $record->gate_type ?? '',
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
            'gate_type' => '',
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
            'editForm.gate_type' => 'nullable|string|max:20',
            'editForm.confidence' => 'required|numeric|min:0|max:1',
        ]);

        $record = Record::find($this->editingRecordId);

        if ($record) {
            $record->update([
                'plate_number' => strtoupper($this->editForm['plate_number']),
                'gate_type' => $this->editForm['gate_type'] ?: null,
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
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.anpr.recent-vehicles-table', [
            'records' => $this->records,
        ]);
    }
}
