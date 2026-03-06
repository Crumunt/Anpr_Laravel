<?php

namespace App\Models;

use App\Helpers\ApplicationDisplayHelper;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    //
    use HasUlids;

    public $incrementing = false;
    protected $primaryKey = "user_id";
    protected $keyType = "string";

    protected $fillable = [
        "user_id",
        "clsu_id",
        "first_name",
        "middle_name",
        "last_name",
        "suffix",
        "region",
        "province",
        "municipality",
        "barangay",
        "zip_code",
        "phone_number",
        "college_unit_department",
        "position",
        "license_number",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function approvedBy()
    {
        return $this->belongsTo(UserDetails::class, "approved_by", "id");
    }

    // SHEESH MAGIC __GET IN LARAVEL SHEEEEEESHHH
    public function getStatusNameAttribute(): ?string
    {
        return ucwords(str_replace("_", " ", $this->status->status_name)) ??
            null;
    }

    public function getStatusBadgeAttribute(): ?string
    {
        return ApplicationDisplayHelper::renderBadgeClass($this->status_name) ??
            null;
    }

    public function getFullNameAttribute(): string
    {
        return ApplicationDisplayHelper::getFullNameAttribute(
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        );
    }

    public function getRegionNameAttribute(): string
    {
        $raw = file_get_contents(storage_path("app/json/cluster.json"));
        $regions = json_decode($raw, true);
        return $regions[$this->region]["region_name"] ?? "Test Region";
    }
}
