<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    //
    use HasUlids;
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        "application_id",
        "vehicle_id",
        "type",
        "file_path",
        'mime_type',
        'file_size',
        "status_id",
        "rejection_reason",
        "reviewed_by",
        "reviewed_at",
        "version",
        "replaced_by",
        "is_current",
        "is_renewal_document",
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'is_renewal_document' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(
            Application::class,
            "application_id",
            "id",
        );
    }

    public function vehicle()
    {
        return $this->belongsTo(
            Vehicle\Vehicle::class,
            "vehicle_id",
            "id",
        );
    }

    public function status()
    {
        return $this->belongsTo(Status::class, "status_id");
    }
}
