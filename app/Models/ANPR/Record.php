<?php

namespace App\Models\ANPR;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    //
    protected $fillable = [
        'observed_plate',
        'status_id',
        'vehicle_type',
    ];
}
