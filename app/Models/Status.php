<?php

namespace App\Models;

use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $fillable = [
        'status_name'
    ];

    public function user() {
        return $this->hasMany(User::class, 'status_id');
    }

    public function vehicle() {
        return $this->hasMany(Vehicle::class, 'status_id');
    }
}
