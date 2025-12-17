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
    ];

    public function applications()
    {
        return $this->belongsTo(
            Application::class,
            "application_id",
            "user_id",
        );
    }

    public function status()
    {
        return $this->belongsTo(Status::class, "status_id");
    }
}
