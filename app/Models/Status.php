<?php

namespace App\Models;

use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $fillable = [
        'code',
        'status_name',
        'description',
    ];

    public function userDetails() {
        return $this->hasMany(UserDetails::class, 'status_id');
    }

    public function vehicle() {
        return $this->hasMany(Vehicle::class, 'status_id');
    }
}
