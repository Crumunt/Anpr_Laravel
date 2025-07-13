<?php

namespace App\Models\Vehicle;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    //
    protected $fillable = [
        'owner_id',
        'license_plate',
        'vehicle_type',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'assigned_gate_pass',
        'status_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
