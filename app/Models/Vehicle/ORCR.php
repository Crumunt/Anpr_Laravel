<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;

class orcr extends Model
{
    //
    protected $table = 'orcr';
    protected $fillable = [
        'vehicle_id',
        'document_path',
    ];
}
