<?php

namespace App\Livewire\ANPR;

use App\Models\ANPR\FlaggedVehicle;
use App\Models\ANPR\Record;
use Livewire\Component;

/**
 * Dashboard Cards Component
 *
 * Displays 24-hour metric cards for the ANPR Dashboard.
 * Uses optimized Eloquent queries to avoid loading entire datasets.
 */
class DashboardCards extends Component
{
    /**
     * Total vehicles detected in the last 24 hours
     */
    public int $totalVehicles = 0;

    /**
     * Number of flagged plates (active flags)
     */
    public int $flaggedPlates = 0;

    /**
     * Number of unique plate numbers detected in last 24 hours
     */
    public int $uniquePlates = 0;

    /**
     * High priority alerts count
     */
    public int $highPriorityAlerts = 0;

    /**
     * Percentage changes from previous period
     */
    public array $percentChanges = [
        'vehicles' => 0,
        'flagged' => 0,
        'unique' => 0,
    ];

    /**
     * Auto-refresh interval in seconds
     */
    public int $refreshInterval = 30;

    /**
     * Initialize the component
     */
    public function mount(): void
    {
        $this->refreshInterval = config('anpr.dashboard.auto_refresh_interval', 30);
        $this->loadMetrics();
    }

    /**
     * Load all dashboard metrics
     */
    public function loadMetrics(): void
    {
        $this->loadVehicleMetrics();
        $this->loadFlaggedMetrics();
        $this->calculatePercentChanges();
    }

    /**
     * Load vehicle detection metrics from the database
     * Uses optimized queries with proper indexing
     */
    protected function loadVehicleMetrics(): void
    {
        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        $stats = Record::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->selectRaw('COUNT(*) as total, COUNT(DISTINCT plate_number) as unique_plates')
            ->first();

        $this->totalVehicles = $stats->total;
        $this->uniquePlates = $stats->unique_plates;
    }

    /**
     * Load flagged vehicle metrics from the database
     */
    protected function loadFlaggedMetrics(): void
    {
        // Count active flagged vehicles
        $this->flaggedPlates = FlaggedVehicle::active()->count();

        // Count high priority active alerts
        $this->highPriorityAlerts = FlaggedVehicle::active()
            ->where('priority', 'high')
            ->count();
    }

    /**
     * Calculate percentage changes from previous 24-hour period
     */
    protected function calculatePercentChanges(): void
    {
        $hours = config('anpr.dashboard.metrics_hours', 24);

        $previousStart = now()->subHours($hours * 2);
        $previousEnd = now()->subHours($hours);
        $currentStart = now()->subHours($hours);

        // Previous vehicle stats
        $previousStats = Record::whereBetween('created_at', [$previousStart, $previousEnd])
            ->selectRaw('COUNT(*) as vehicles, COUNT(DISTINCT plate_number) as unique_plates')
            ->first();

        $previousVehicles = $previousStats->vehicles;
        $previousUnique = $previousStats->unique_plates;

        // Vehicle percentage
        $this->percentChanges['vehicles'] = $previousVehicles > 0
            ? round((($this->totalVehicles - $previousVehicles) / $previousVehicles) * 100, 1)
            : ($this->totalVehicles > 0 ? 100 : 0);

        // Unique plates percentage
        $this->percentChanges['unique'] = $previousUnique > 0
            ? round((($this->uniquePlates - $previousUnique) / $previousUnique) * 100, 1)
            : ($this->uniquePlates > 0 ? 100 : 0);

        // Flagged vehicles
        $yesterdayFlags = FlaggedVehicle::whereBetween('created_at', [$previousStart, $previousEnd])->count();

        $todayFlags = FlaggedVehicle::whereBetween('created_at', [$currentStart, now()])->count();

        $this->percentChanges['flagged'] = $yesterdayFlags > 0
            ? round((($todayFlags - $yesterdayFlags) / $yesterdayFlags) * 100, 1)
            : ($todayFlags > 0 ? 100 : 0);
    }

    /**
     * Refresh metrics (called by wire:poll)
     */
    public function refresh(): void
    {
        $this->loadMetrics();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.anpr.dashboard-cards');
    }
}
