<?php

namespace App\Models\ANPR;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    //
    use HasUlids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'plate_number',
        'confidence',
        'bbox_x1',
        'bbox_y1',
        'bbox_x2',
        'bbox_y2',
        'image_path',
        'frame_number',
        'camera_id',
        'gate_type',
        'location',
        'detected_at',
        'raw_data',
    ];

    protected $casts = [
        'confidence' => 'float',
        'bbox_x1' => 'float',
        'bbox_y1' => 'float',
        'bbox_x2' => 'float',
        'bbox_y2' => 'float',
        'frame_number' => 'integer',
        'detected_at' => 'datetime',
        'raw_data' => 'array',
    ];
}
