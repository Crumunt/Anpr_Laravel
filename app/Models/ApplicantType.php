<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicantType extends Model
{
    use HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'label',
        'description',
        'requires_clsu_id',
        'requires_department',
        'requires_position',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'requires_clsu_id' => 'boolean',
        'requires_department' => 'boolean',
        'requires_position' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the required documents for this applicant type.
     */
    public function requiredDocuments(): HasMany
    {
        return $this->hasMany(ApplicantTypeDocument::class)->orderBy('sort_order');
    }

    /**
     * Get the applications for this applicant type.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Scope to get only active applicant types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get active applicant types for dropdown options.
     */
    public static function getDropdownOptions(): array
    {
        return static::active()
            ->ordered()
            ->pluck('label', 'id')
            ->toArray();
    }

    /**
     * Get applicant type by its name.
     */
    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }
}
