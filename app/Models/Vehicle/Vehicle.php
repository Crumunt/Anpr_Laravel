<?php

namespace App\Models\Vehicle;

use App\Helpers\ApplicationDisplayHelper;
use App\Models\Application;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    //
    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'application_id',
        'owner_id',
        'plate_number',
        'type',
        'make',
        'model',
        'year',
        'color',
        'assigned_gate_pass',
        'previous_gate_pass',
        'gate_pass_assignment_count',
        'status_id',
        'validity_years',
        'approved_at',
        'expires_at',
        'is_renewal',
        'renewed_from_vehicle_id',
        'renewal_requested_at',
        'has_pending_renewal',
        'pending_renewal_application_id',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'expires_at' => 'datetime',
        'renewal_requested_at' => 'datetime',
        'is_renewal' => 'boolean',
        'has_pending_renewal' => 'boolean',
        'validity_years' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Get the original vehicle this was renewed from.
     */
    public function renewedFrom()
    {
        return $this->belongsTo(Vehicle::class, 'renewed_from_vehicle_id');
    }

    /**
     * Get vehicles that are renewals of this vehicle.
     */
    public function renewals()
    {
        return $this->hasMany(Vehicle::class, 'renewed_from_vehicle_id');
    }

    public function getVehicleInfoAttribute(): string
    {
        $year = $this->year ?? '';
        $make = $this->make ?? '';
        $model = $this->model ?? '';
        return trim("$year $make $model");
    }

    public function getStatusBadgeAttribute() {
        $statusBadgeClass = ApplicationDisplayHelper::renderBadgeClass($this->status?->status_name);
        return ['label' => $this->status?->status_name, 'class' => $statusBadgeClass];
    }

    /**
     * Check if the gate pass is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the gate pass is expiring soon (within warning period).
     * Only returns true for active vehicles with gate passes.
     */
    public function isExpiringSoon(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        // Only consider vehicles with active status
        if ($this->status?->code !== 'active') {
            return false;
        }

        $warningDays = config('anpr.gate_pass.renewal_warning_days', 90);
        $daysUntilExpiration = (int) abs(now()->diffInDays($this->expires_at));

        return $this->expires_at->isFuture() && $daysUntilExpiration <= $warningDays;
    }

    /**
     * Check if the gate pass is active and valid.
     */
    public function isActive(): bool
    {
        return $this->approved_at &&
               $this->expires_at &&
               $this->expires_at->isFuture() &&
               $this->status?->code === 'active';
    }

    /**
     * Get the number of days until expiration.
     */
    public function getDaysUntilExpirationAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }

    /**
     * Get human-readable time until expiration.
     * Shows years/months for distant dates, days when closer.
     */
    public function getTimeUntilExpirationAttribute(): ?string
    {
        if (!$this->expires_at) {
            return null;
        }

        $now = now();
        $expiresAt = $this->expires_at;

        if ($expiresAt->isPast()) {
            return 'Expired';
        }

        // Cast to int as Carbon 3.x returns floats
        $years = (int) $now->diffInYears($expiresAt);
        $months = (int) $now->copy()->addYears($years)->diffInMonths($expiresAt);
        $days = (int) $now->copy()->addYears($years)->addMonths($months)->diffInDays($expiresAt);

        $parts = [];

        if ($years > 0) {
            $parts[] = $years . ' ' . ($years === 1 ? 'year' : 'years');
        }

        if ($months > 0) {
            $parts[] = $months . ' ' . ($months === 1 ? 'month' : 'months');
        }

        // Only show days if less than 1 year, or if years and months are both 0
        if ($years === 0 || ($years === 0 && $months === 0)) {
            if ($days > 0 || empty($parts)) {
                $parts[] = $days . ' ' . ($days === 1 ? 'day' : 'days');
            }
        }

        return implode(', ', $parts) . ' left';
    }

    /**
     * Get the expiration status label.
     */
    public function getExpirationStatusAttribute(): array
    {
        if (!$this->expires_at) {
            return ['label' => 'Not Set', 'class' => 'bg-gray-100 text-gray-800'];
        }

        if ($this->isExpired()) {
            return ['label' => 'Expired', 'class' => 'bg-red-100 text-red-800'];
        }

        if ($this->isExpiringSoon()) {
            $days = $this->days_until_expiration;
            return ['label' => "Expires in {$days} days", 'class' => 'bg-amber-100 text-amber-800'];
        }

        return ['label' => 'Active', 'class' => 'bg-emerald-100 text-emerald-800'];
    }

    /**
     * Check if renewal is allowed for this vehicle.
     */
    public function canRenew(): bool
    {
        // Can only renew if approved and has active status
        if (!$this->approved_at || $this->status?->code !== 'active') {
            return false;
        }

        // Check if there's already a pending renewal for this vehicle
        if ($this->has_pending_renewal) {
            return false;
        }

        // Allow renewal if expired or expiring soon (within warning period)
        if (!$this->expires_at) {
            return false;
        }

        $warningDays = config('anpr.gate_pass.renewal_warning_days', 90);
        $daysUntilExpiration = (int) abs(now()->diffInDays($this->expires_at));

        return $this->expires_at->isPast() || ($this->expires_at->isFuture() && $daysUntilExpiration <= $warningDays);
    }

    /**
     * Get the pending renewal application for this vehicle.
     */
    public function pendingRenewalApplication()
    {
        return $this->belongsTo(Application::class, 'pending_renewal_application_id');
    }

    /**
     * Get renewal documents linked directly to this vehicle.
     */
    public function renewalDocuments()
    {
        return $this->hasMany(\App\Models\Documents::class, 'vehicle_id')
            ->where('is_renewal_document', true);
    }

    /**
     * Scope for expired vehicles.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                     ->where('expires_at', '<', now());
    }

    /**
     * Scope for vehicles expiring soon.
     */
    public function scopeExpiringSoon($query)
    {
        $warningDays = config('anpr.gate_pass.renewal_warning_days', 90);

        return $query->whereNotNull('expires_at')
                     ->where('expires_at', '>', now())
                     ->where('expires_at', '<=', now()->addDays($warningDays));
    }

    /**
     * Scope for active (non-expired) vehicles.
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('expires_at')
                     ->where('expires_at', '>', now());
    }

    /**
     * Set expiration date based on validity years from a given date.
     */
    public function setExpirationFromDate(Carbon $approvalDate, ?int $validityYears = null): void
    {
        $years = $validityYears ?? $this->validity_years ?? config('anpr.gate_pass.default_validity_years', 4);

        $this->validity_years = $years;
        $this->approved_at = $approvalDate;
        $this->expires_at = $approvalDate->copy()->addYears($years);
    }
}
