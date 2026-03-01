<?php

namespace App\Models\ANPR;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * FlaggedVehicle Model
 *
 * Represents a vehicle that has been flagged for security review.
 */
class FlaggedVehicle extends Model
{
    use HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'flagged_vehicles';

    protected $fillable = [
        'plate_number',
        'reason',
        'reason_label',
        'priority',
        'notes',
        'status',
        'vehicle_make',
        'vehicle_model',
        'vehicle_color',
        'vehicle_type',
        'record_id',
        'flagged_by_id',
        'flagged_by_name',
        'flagged_by_role',
        'resolved_by_id',
        'resolved_by_name',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Reason labels mapping
     */
    public const REASON_LABELS = [
        'suspicious' => 'Suspicious Activity',
        'expired' => 'Expired Pass',
        'unauthorized' => 'Unauthorized Entry',
        'stolen' => 'Reported Stolen',
        'unregistered' => 'Unregistered Vehicle',
        'other' => 'Other',
    ];

    /**
     * Priority configurations
     */
    public const PRIORITY_CONFIG = [
        'high' => [
            'label' => 'High Priority',
            'color' => 'red',
            'bgClass' => 'bg-red-100 text-red-700',
            'icon' => 'exclamation-circle',
        ],
        'medium' => [
            'label' => 'Medium Priority',
            'color' => 'amber',
            'bgClass' => 'bg-amber-100 text-amber-700',
            'icon' => 'exclamation-triangle',
        ],
        'low' => [
            'label' => 'Low Priority',
            'color' => 'blue',
            'bgClass' => 'bg-blue-100 text-blue-700',
            'icon' => 'info-circle',
        ],
    ];

    /**
     * Get the ANPR record associated with this flag
     */
    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class, 'record_id');
    }

    /**
     * Get the user who flagged this vehicle
     */
    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by_id');
    }

    /**
     * Get the user who resolved this flag
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_id');
    }

    /**
     * Get the human-readable reason label
     */
    public function getReasonDisplayAttribute(): string
    {
        return $this->reason_label ?: (self::REASON_LABELS[$this->reason] ?? ucfirst($this->reason));
    }

    /**
     * Get priority configuration
     */
    public function getPriorityConfigAttribute(): array
    {
        return self::PRIORITY_CONFIG[$this->priority] ?? self::PRIORITY_CONFIG['medium'];
    }

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return $this->priority_config['bgClass'];
    }

    /**
     * Get priority icon
     */
    public function getPriorityIconAttribute(): string
    {
        return $this->priority_config['icon'];
    }

    /**
     * Get vehicle info string
     */
    public function getVehicleInfoAttribute(): string
    {
        $parts = array_filter([
            $this->vehicle_color,
            $this->vehicle_make,
            $this->vehicle_model,
        ]);

        return !empty($parts) ? implode(' ', $parts) : 'Unknown Vehicle';
    }

    /**
     * Scope: Active flags only
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Resolved flags only
     */
    public function scopeResolved(Builder $query): Builder
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope: By priority
     */
    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        if ($priority === 'all') {
            return $query;
        }
        return $query->where('priority', $priority);
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
     * Scope: Records within date range
     */
    public function scopeWithinDateRange(Builder $query, string $range): Builder
    {
        $startDate = match($range) {
            '24hours' => now()->subHours(24),
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            'all' => now()->subYears(10),
            default => now()->subDays(7),
        };

        return $query->where('created_at', '>=', $startDate);
    }

    /**
     * Scope: Resolved today
     */
    public function scopeResolvedToday(Builder $query): Builder
    {
        return $query->where('status', 'resolved')
            ->whereDate('resolved_at', today());
    }

    /**
     * Mark as resolved
     */
    public function resolve(?User $user = null, ?string $notes = null): bool
    {
        $this->status = 'resolved';
        $this->resolved_at = now();
        $this->resolution_notes = $notes;

        if ($user) {
            $this->resolved_by_id = $user->id;
            $this->resolved_by_name = $user->name;
        }

        return $this->save();
    }

    /**
     * Mark as dismissed
     */
    public function dismiss(?User $user = null, ?string $notes = null): bool
    {
        $this->status = 'dismissed';
        $this->resolved_at = now();
        $this->resolution_notes = $notes;

        if ($user) {
            $this->resolved_by_id = $user->id;
            $this->resolved_by_name = $user->name;
        }

        return $this->save();
    }

    /**
     * Reactivate a resolved/dismissed flag
     */
    public function reactivate(): bool
    {
        $this->status = 'active';
        $this->resolved_at = null;
        $this->resolved_by_id = null;
        $this->resolved_by_name = null;
        $this->resolution_notes = null;

        return $this->save();
    }
}
