<?php

namespace App\Models\Vehicle;

use App\Helpers\ApplicationDisplayHelper;
use App\Models\Status;
use App\Models\User;
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
        'owner_id',
        'plate_number',
        'type',
        'make',
        'model',
        'year',
        'color',
        'assigned_gate_pass',
        'status_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
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
}
