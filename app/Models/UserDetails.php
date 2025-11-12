<?php

namespace App\Models;

use App\ApplicantType;
use App\Helpers\ApplicationDisplayHelper;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';

    protected $casts = [
        'applicant_type' => ApplicantType::class,
    ];
    protected $fillable = [
        'user_id',
        'clsu_id',
        'current_address',
        'street_address',
        'barangay',
        'city_municipality',
        'province',
        'postal_code',
        'country',
        'license_number',
        'college_unit_department',
        'phone_number',
        'applicant_type',
        'approved_by',
        'status_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(UserDetails::class, 'approved_by', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function setStatusByCode(string $code)
    {
        $status = Status::where('code', $code)->firstOrFail();
        $this->status()->associate($status);
    }

    // SHEESH MAGIC __GET IN LARAVEL SHEEEEEESHHH
    public function getStatusNameAttribute(): ?string
    {
        return ucwords(str_replace('_', ' ', $this->status->status_name)) ?? null;
    }

    public function getStatusBadgeAttribute(): ?string
    {
        return ApplicationDisplayHelper::renderBadgeClass($this->status_name)
            ?? null;
    }
}
