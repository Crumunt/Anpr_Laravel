<?php

namespace App\Livewire\ANPR;

use App\Models\ANPR\Gate;
use App\Models\ANPR\Record;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Url;

/**
 * Analytics Component
 *
 * Comprehensive analytics dashboard with report generation,
 * traffic analysis, and data visualization.
 */
class Analytics extends Component
{
    /**
     * Date range selection
     */
    #[Url(as: 'range')]
    public string $dateRange = '7days';

    /**
     * Gate filter
     */
    #[Url(as: 'gate')]
    public string $gateFilter = 'all';

    /**
     * Gate location filter (Entry/Exit)
     */
    #[Url(as: 'direction')]
    public string $locationFilter = 'all';

    /**
     * Custom date range start
     */
    public ?string $customStartDate = null;

    /**
     * Custom date range end
     */
    public ?string $customEndDate = null;

    /**
     * Show report modal
     */
    public bool $showReportModal = false;

    /**
     * Available gates
     */
    public array $availableGates = [];

    /**
     * Available gate locations
     */
    public array $availableLocations = [];

    /**
     * Summary statistics
     */
    public array $stats = [];

    /**
     * Hourly traffic data
     */
    public array $hourlyData = [];

    /**
     * Daily traffic data
     */
    public array $dailyData = [];

    /**
     * Gate distribution data
     */
    public array $gateDistribution = [];

    /**
     * Top plates data
     */
    public array $topPlates = [];

    /**
     * Flagged summary
     */
    public array $flaggedSummary = [];

    /**
     * Top plates limit
     */
    public int $topPlatesLimit = 10;

    /**
     * Show all hours in hourly chart
     */
    public bool $showAllHours = false;

    /**
     * Report form data
     */
    public array $reportForm = [
        'type' => 'summary',
        'format' => 'csv',
        'start_date' => null,
        'end_date' => null,
    ];

    /**
     * Mount the component
     */
    public function mount(): void
    {
        $this->loadAvailableGates();
        $this->loadAvailableLocations();

        $this->reportForm['start_date'] = now()->subDays(7)->format('Y-m-d');
        $this->reportForm['end_date'] = now()->format('Y-m-d');

        $this->loadAllData();
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

        // If no gates in database yet, fall back to config
        if (empty($this->availableGates)) {
            $this->availableGates = config('anpr.gates', [
                'entry' => 'Entry Gate',
                'exit' => 'Exit Gate',
                'parking' => 'Parking Gate',
            ]);
        }
    }

    /**
     * Load available gate locations
     */
    protected function loadAvailableLocations(): void
    {
        $this->availableLocations = [
            Gate::LOCATION_ENTRY => Gate::LOCATION_ENTRY,
            Gate::LOCATION_EXIT => Gate::LOCATION_EXIT,
        ];
    }

    /**
     * Load all analytics data
     */
    public function loadAllData(): void
    {
        $this->loadStats();
        $this->loadHourlyData();
        $this->loadDailyData();
        $this->loadGateDistribution();
        $this->loadTopPlates();
        $this->loadFlaggedSummary();
    }

    /**
     * Get the date range start
     */
    protected function getDateRangeStart(): \DateTime
    {
        return match($this->dateRange) {
            '24hours' => now()->subHours(24),
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            'custom' => $this->customStartDate
                ? \Carbon\Carbon::parse($this->customStartDate)->startOfDay()
                : now()->subDays(7),
            default => now()->subDays(7),
        };
    }

    /**
     * Get the date range end
     */
    protected function getDateRangeEnd(): \DateTime
    {
        if ($this->dateRange === 'custom' && $this->customEndDate) {
            return \Carbon\Carbon::parse($this->customEndDate)->endOfDay();
        }
        return now();
    }

    /**
     * Load summary statistics
     */
    public function loadStats(): void
    {
        $startDate = $this->getDateRangeStart();
        $endDate = $this->getDateRangeEnd();

        $query = Record::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($this->gateFilter !== 'all', fn($q) => $q->byGateName($this->gateFilter))
            ->when($this->locationFilter !== 'all', fn($q) => $q->byGateLocation($this->locationFilter));

        $this->stats = [
            'total_detections' => $query->count(),
            'flagged_count' => (clone $query)->get()->filter(fn($r) => $r->is_flagged)->count(),
            'unique_plates' => (clone $query)->distinct('plate_number')->count('plate_number'),
            'avg_confidence' => round((clone $query)->avg('confidence') * 100 ?? 0, 1),
        ];
    }

    /**
     * Load hourly traffic data
     */
    public function loadHourlyData(): void
    {
        $startDate = $this->getDateRangeStart();
        $endDate = $this->getDateRangeEnd();

        $hourlyRecords = Record::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($this->gateFilter !== 'all', fn($q) => $q->byGateName($this->gateFilter))
            ->when($this->locationFilter !== 'all', fn($q) => $q->byGateLocation($this->locationFilter))
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $this->hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $record = $hourlyRecords->firstWhere('hour', $i);
            $this->hourlyData[] = [
                'hour' => str_pad($i, 2, '0', STR_PAD_LEFT),
                'count' => $record ? $record->count : 0,
            ];
        }
    }

    /**
     * Load daily traffic data
     */
    public function loadDailyData(): void
    {
        $startDate = $this->getDateRangeStart();
        $endDate = $this->getDateRangeEnd();

        $dailyRecords = Record::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($this->gateFilter !== 'all', fn($q) => $q->byGateName($this->gateFilter))
            ->when($this->locationFilter !== 'all', fn($q) => $q->byGateLocation($this->locationFilter))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->dailyData = $dailyRecords->map(fn($r) => [
            'date' => $r->date,
            'count' => $r->count,
        ])->toArray();

        // Fill in missing dates with zero
        if (empty($this->dailyData)) {
            $current = clone $startDate;
            while ($current <= $endDate) {
                $this->dailyData[] = [
                    'date' => $current->format('Y-m-d'),
                    'count' => 0,
                ];
                $current->modify('+1 day');
            }
        }
    }

    /**
     * Load gate distribution data
     */
    public function loadGateDistribution(): void
    {
        $startDate = $this->getDateRangeStart();
        $endDate = $this->getDateRangeEnd();

        // Use location and gate_type columns directly (they store gate name and direction)
        $gateRecords = Record::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($this->locationFilter !== 'all', fn($q) => $q->byGateLocation($this->locationFilter))
            ->selectRaw('COALESCE(location, "Unknown") as gate_name, COALESCE(gate_type, "unknown") as gate_location, COUNT(*) as count')
            ->groupBy('location', 'gate_type')
            ->orderByDesc('count')
            ->get();

        $this->gateDistribution = $gateRecords->map(fn($r) => [
            'gate_name' => $r->gate_name ?: 'Unknown',
            'gate_location' => ucfirst($r->gate_location ?: 'unknown'),
            'gate_type' => $r->gate_name ?: 'Unknown', // For backward compatibility
            'count' => $r->count,
        ])->toArray();
    }

    /**
     * Load top detected plates
     */
    public function loadTopPlates(): void
    {
        $startDate = $this->getDateRangeStart();
        $endDate = $this->getDateRangeEnd();

        $topPlates = Record::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($this->gateFilter !== 'all', fn($q) => $q->byGateName($this->gateFilter))
            ->when($this->locationFilter !== 'all', fn($q) => $q->byGateLocation($this->locationFilter))
            ->selectRaw('plate_number, COUNT(*) as count, AVG(confidence) * 100 as avg_confidence, MAX(created_at) as last_seen')
            ->groupBy('plate_number')
            ->orderByDesc('count')
            ->limit($this->topPlatesLimit)
            ->get();

        $this->topPlates = $topPlates->map(fn($r) => [
            'plate_number' => $r->plate_number,
            'count' => $r->count,
            'avg_confidence' => round($r->avg_confidence, 1),
            'last_seen' => $r->last_seen,
        ])->toArray();
    }

    /**
     * Load flagged vehicles summary
     */
    public function loadFlaggedSummary(): void
    {
        $startDate = $this->getDateRangeStart();
        $endDate = $this->getDateRangeEnd();

        $allRecords = Record::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $total = $allRecords->count();
        $flagged = $allRecords->filter(fn($r) => $r->is_flagged)->count();

        $this->flaggedSummary = [
            'total' => $flagged,
            'pending' => $flagged,
            'resolved' => 0,
            'flag_rate' => $total > 0 ? round(($flagged / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Handle date range change
     */
    public function updatedDateRange(): void
    {
        $this->loadAllData();
    }

    /**
     * Handle custom start date change
     */
    public function updatedCustomStartDate(): void
    {
        if ($this->dateRange === 'custom') {
            $this->loadAllData();
        }
    }

    /**
     * Handle custom end date change
     */
    public function updatedCustomEndDate(): void
    {
        if ($this->dateRange === 'custom') {
            $this->loadAllData();
        }
    }

    /**
     * Handle gate filter change
     */
    public function updatedGateFilter(): void
    {
        $this->loadAllData();
    }

    /**
     * Handle location filter change
     */
    public function updatedLocationFilter(): void
    {
        $this->loadAllData();
    }

    /**
     * Handle top plates limit change
     */
    public function updatedTopPlatesLimit(): void
    {
        $this->loadTopPlates();
    }

    /**
     * Refresh data
     */
    public function refreshData(): void
    {
        $this->loadAllData();
    }

    /**
     * Open report generation modal
     */
    public function openReportModal(): void
    {
        $this->showReportModal = true;
    }

    /**
     * Close report generation modal
     */
    public function closeReportModal(): void
    {
        $this->showReportModal = false;
    }

    /**
     * Generate report — stores params in session and redirects to controller download route
     */
    public function generateReport()
    {
        $this->validate([
            'reportForm.type' => 'required|in:summary,detailed,flagged',
            'reportForm.format' => 'required|in:csv,pdf,excel',
            'reportForm.start_date' => 'required|date',
            'reportForm.end_date' => 'required|date|after_or_equal:reportForm.start_date',
        ]);

        // Store report params in session for the download controller
        session()->put('anpr_report_params', [
            'type' => $this->reportForm['type'],
            'format' => $this->reportForm['format'],
            'start_date' => $this->reportForm['start_date'],
            'end_date' => $this->reportForm['end_date'],
            'gate_filter' => $this->gateFilter,
            'location_filter' => $this->locationFilter,
        ]);

        $this->closeReportModal();

        // Use JS to open the download URL in a new tab so the current page stays intact
        $this->js("window.open('" . route('anpr.analytics.download-report') . "', '_blank')");
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.anpr.analytics');
    }
}
