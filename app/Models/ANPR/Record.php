<?php

namespace App\Models\ANPR;

use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Record extends Model
{
    use HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'plate_number',
        'confidence',
        'bbox_x1',
        'bbox_y1',
        'bbox_x2',
        'bbox_y2',
        'camera_id',
        'gate_type',
        'gate_id',
        'location',
        'detected_at',
        'is_flagged',
        'gate_pass_number',
        'vehicle_id',
    ];

    protected $casts = [
        'confidence' => 'float',
        'bbox_x1' => 'float',
        'bbox_y1' => 'float',
        'bbox_x2' => 'float',
        'bbox_y2' => 'float',
        'detected_at' => 'datetime',
        'is_flagged' => 'boolean',
    ];

    /**
     * Get the registered vehicle associated with this detection
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the gate associated with this detection
     */
    public function gate(): BelongsTo
    {
        return $this->belongsTo(Gate::class);
    }

    /**
     * Check if this detection has a registered gate pass
     */
    public function getHasGatePassAttribute(): bool
    {
        return !empty($this->gate_pass_number);
    }

    /**
     * Get the flagged status.
     */
    public function getIsFlaggedAttribute($value): bool
    {
        return (bool) $value;
    }

    /**
     * Toggle the flagged status of this record.
     * Updates the database and syncs with FlaggedVehicle table.
     */
    public function toggleFlag(): bool
    {
        $newStatus = !$this->is_flagged;

        // Update the is_flagged column on this record
        $this->update(['is_flagged' => $newStatus]);

        if ($newStatus) {
            // Create a FlaggedVehicle entry if flagging
            $this->createFlaggedVehicleEntry();
        } else {
            // Remove or resolve the FlaggedVehicle entry if unflagging
            $this->removeFlaggedVehicleEntry();
        }

        return $newStatus;
    }

    /**
     * Create a FlaggedVehicle entry for this record
     */
    protected function createFlaggedVehicleEntry(): void
    {
        $user = Auth::user();

        // Check if already flagged
        $existingFlag = FlaggedVehicle::where('plate_number', $this->plate_number)
            ->where('status', 'active')
            ->first();

        if ($existingFlag) {
            return; // Already flagged, don't duplicate
        }

        FlaggedVehicle::create([
            'plate_number' => $this->plate_number,
            'reason' => 'other',
            'reason_label' => 'Quick Flag',
            'priority' => 'medium',
            'notes' => 'Flagged from vehicle detection table',
            'status' => 'active',
            'record_id' => $this->id,
            'flagged_by_id' => $user?->id,
            'flagged_by_name' => $user?->name ?? 'System',
            'flagged_by_role' => $user?->roles->first()?->name ?? 'Security Officer',
        ]);
    }

    /**
     * Remove or resolve the FlaggedVehicle entry for this record
     */
    protected function removeFlaggedVehicleEntry(): void
    {
        $user = Auth::user();

        // Find the active flag for this plate or record
        $flag = FlaggedVehicle::where(function ($query) {
                $query->where('plate_number', $this->plate_number)
                    ->orWhere('record_id', $this->id);
            })
            ->where('status', 'active')
            ->first();

        if ($flag) {
            $flag->update([
                'status' => 'resolved',
                'resolved_by_id' => $user?->id,
                'resolved_by_name' => $user?->name ?? 'System',
                'resolution_notes' => 'Unflagged from vehicle detection table',
                'resolved_at' => now(),
            ]);
        }
    }

    /**
     * Scope: Records from the last N hours
     */
    public function scopeLastHours(Builder $query, int $hours = 24): Builder
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope: Records detected in the last N hours
     */
    public function scopeDetectedLastHours(Builder $query, int $hours = 24): Builder
    {
        return $query->where('detected_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope: Filter by gate type (legacy support)
     */
    public function scopeByGate(Builder $query, ?string $gateType): Builder
    {
        if (empty($gateType) || $gateType === 'all') {
            return $query;
        }
        return $query->where('location', $gateType);
    }

    /**
     * Scope: Filter by gate ID
     */
    public function scopeByGateId(Builder $query, ?int $gateId): Builder
    {
        if (empty($gateId)) {
            return $query;
        }
        return $query->where('gate_id', $gateId);
    }

    /**
     * Scope: Filter by gate name (e.g., "Main Gate", "Second Gate")
     */
    public function scopeByGateName(Builder $query, ?string $gateName): Builder
    {
        if (empty($gateName) || $gateName === 'all') {
            return $query;
        }
        // Filter by location column which stores the gate name (e.g., "Main Gate")
        return $query->where('location', $gateName);
    }

    /**
     * Scope: Filter by gate location (Entry/Exit)
     */
    public function scopeByGateLocation(Builder $query, ?string $location): Builder
    {
        if (empty($location) || $location === 'all') {
            return $query;
        }
        // Filter by gate_type column which stores direction as lowercase (e.g., "entry", "exit")
        return $query->whereRaw('LOWER(gate_type) = ?', [strtolower($location)]);
    }

    /**
     * Scope: Flagged records only
     */
    public function scopeFlagged(Builder $query): Builder
    {
        if (config('anpr.flagging.simulation_enabled', true)) {
            // In simulation mode, we need to filter in PHP
            // This is less efficient but necessary for simulation
            return $query;
        }
        return $query->where('is_flagged', true);
    }

    /**
     * Scope: Search by plate number
     */
    public function scopeSearchPlate(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }
        return $query->where('plate_number', 'like', "%{$search}%");
    }

    /**
     * Get the gate display name
     */
    public function getGateDisplayNameAttribute(): string
    {
        // First check if we have a gate relationship
        if ($this->gate_id && $this->relationLoaded('gate') && $this->gate) {
            return $this->gate->display_name;
        }

        // Lazy load gate if needed
        if ($this->gate_id && $gate = $this->gate) {
            return $gate->display_name;
        }

        // Fall back to legacy gate_type
        $gates = config('anpr.gates', []);
        return $gates[$this->gate_type] ?? $this->location ?? $this->gate_type ?? 'Unknown Gate';
    }

    /**
     * Get formatted confidence as percentage
     */
    public function getConfidencePercentAttribute(): float
    {
        return round($this->confidence * 100, 1);
    }

    /**
     * Get status display text
     */
    public function getStatusDisplayAttribute(): string
    {
        return $this->is_flagged ? 'Flagged' : 'Normal';
    }

    /**
     * Get status color class
     */
    public function getStatusColorAttribute(): string
    {
        return $this->is_flagged ? 'red' : 'green';
    }
}
