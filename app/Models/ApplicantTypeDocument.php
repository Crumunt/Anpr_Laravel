<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantTypeDocument extends Model
{
    use HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'applicant_type_id',
        'name',
        'label',
        'description',
        'accepted_formats',
        'max_file_size',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'max_file_size' => 'integer',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the applicant type that owns this document requirement.
     */
    public function applicantType(): BelongsTo
    {
        return $this->belongsTo(ApplicantType::class);
    }

    /**
     * Get accepted formats as an array.
     */
    public function getAcceptedFormatsArrayAttribute(): array
    {
        return array_map('trim', explode(',', $this->accepted_formats));
    }

    /**
     * Get the validation rule string for this document.
     */
    public function getValidationRuleAttribute(): string
    {
        $mimes = $this->accepted_formats;
        $maxSize = $this->max_file_size;

        $rule = "file|mimes:{$mimes}|max:{$maxSize}";

        if ($this->is_required) {
            $rule = "required|{$rule}";
        } else {
            $rule = "nullable|{$rule}";
        }

        return $rule;
    }

    /**
     * Get friendly file size display.
     */
    public function getMaxFileSizeDisplayAttribute(): string
    {
        if ($this->max_file_size >= 1024) {
            return round($this->max_file_size / 1024, 1) . ' MB';
        }
        return $this->max_file_size . ' KB';
    }
}
