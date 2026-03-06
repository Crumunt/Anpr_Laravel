<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    //
    use HasUlids;

    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        "user_id",
        "applicant_type_id",
        "approved_by",
        "status_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function documents()
    {
        return $this->hasMany(Documents::class, "application_id", "id");
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle\Vehicle::class, "application_id", "id");
    }

    /**
     * Get the applicant type for this application (new dynamic relationship).
     */
    public function applicantTypeModel(): BelongsTo
    {
        return $this->belongsTo(ApplicantType::class, 'applicant_type_id');
    }

    /**
     * Get the applicant type label.
     */
    public function getApplicantTypeLabelAttribute(): string
    {
        // Get from the relationship
        if ($this->applicantTypeModel) {
            return $this->applicantTypeModel->label;
        }

        return 'Unknown';
    }
}
