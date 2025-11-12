<?php

namespace App\Models\ANPR;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    //
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'gate_detected',
        'direction',
        'observed_plate',
    ];
}
