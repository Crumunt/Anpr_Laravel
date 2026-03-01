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
        $hours = config('anpr.dashboard.metrics_hours', 24);
        $cutoffTime = now()->subHours($hours);

        // Count total vehicles in the last 24 hours
        $this->totalVehicles = Record::where('created_at', '>=', $cutoffTime)->count();

        // Count unique plate numbers in the last 24 hours
        $this->uniquePlates = Record::where('created_at', '>=', $cutoffTime)
            ->distinct('plate_number')
            ->count('plate_number');
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

        // Previous period vehicle count
        $previousVehicles = Record::whereBetween('created_at', [$previousStart, $previousEnd])->count();

        // Calculate vehicle percentage change
        if ($previousVehicles > 0) {
            $this->percentChanges['vehicles'] = round(
                (($this->totalVehicles - $previousVehicles) / $previousVehicles) * 100,
                1
            );
        } else {
            $this->percentChanges['vehicles'] = $this->totalVehicles > 0 ? 100 : 0;
        }

        // Previous period unique plates
        $previousUnique = Record::whereBetween('created_at', [$previousStart, $previousEnd])
            ->distinct('plate_number')
            ->count('plate_number');

        if ($previousUnique > 0) {
            $this->percentChanges['unique'] = round(
                (($this->uniquePlates - $previousUnique) / $previousUnique) * 100,
                1
            );
        } else {
            $this->percentChanges['unique'] = $this->uniquePlates > 0 ? 100 : 0;
        }

        // Flagged vehicles change (compare to flags created yesterday)
        $yesterdayFlags = FlaggedVehicle::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $todayFlags = FlaggedVehicle::where('created_at', '>=', $currentStart)->count();

        if ($yesterdayFlags > 0) {
            $this->percentChanges['flagged'] = round(
                (($todayFlags - $yesterdayFlags) / $yesterdayFlags) * 100,
                1
            );
        } else {
            $this->percentChanges['flagged'] = $todayFlags > 0 ? 100 : 0;
        }
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
