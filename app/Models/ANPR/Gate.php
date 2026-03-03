<?php

namespace App\Models\ANPR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Gate extends Model
{
    protected $fillable = [
        'gate_name',
        'gate_location',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Gate location options
     */
    public const LOCATION_ENTRY = 'Entry';
    public const LOCATION_EXIT = 'Exit';

    /**
     * Get all available gate locations
     */
    public static function getLocations(): array
    {
        return [
            self::LOCATION_ENTRY,
            self::LOCATION_EXIT,
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug before creating
        static::creating(function ($gate) {
            if (empty($gate->slug)) {
                $gate->slug = Str::slug($gate->gate_name . '-' . $gate->gate_location);
            }
        });
    }

    /**
     * Get records for this gate
     */
    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    /**
     * Scope: Active gates only
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by gate name
     */
    public function scopeByName(Builder $query, string $gateName): Builder
    {
        return $query->where('gate_name', $gateName);
    }

    /**
     * Scope: Filter by location (Entry/Exit)
     */
    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('gate_location', $location);
    }

    /**
     * Scope: Entry gates only
     */
    public function scopeEntry(Builder $query): Builder
    {
        return $query->where('gate_location', self::LOCATION_ENTRY);
    }

    /**
     * Scope: Exit gates only
     */
    public function scopeExit(Builder $query): Builder
    {
        return $query->where('gate_location', self::LOCATION_EXIT);
    }

    /**
     * Get full display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->gate_name} - {$this->gate_location}";
    }

    /**
     * Get unique gate names (for filtering)
     */
    public static function getUniqueGateNames(): array
    {
        return static::active()
            ->select('gate_name')
            ->distinct()
            ->orderBy('gate_name')
            ->pluck('gate_name')
            ->toArray();
    }

    /**
     * Get gates grouped by name for dropdown
     */
    public static function getGroupedForDropdown(): array
    {
        $gates = static::active()->orderBy('gate_name')->orderBy('gate_location')->get();

        $grouped = [];
        foreach ($gates as $gate) {
            if (!isset($grouped[$gate->gate_name])) {
                $grouped[$gate->gate_name] = [];
            }
            $grouped[$gate->gate_name][] = [
                'id' => $gate->id,
                'location' => $gate->gate_location,
                'slug' => $gate->slug,
                'display_name' => $gate->display_name,
            ];
        }

        return $grouped;
    }

    /**
     * Find gate by slug
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
