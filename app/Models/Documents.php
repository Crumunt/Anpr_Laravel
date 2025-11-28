<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    //
    use HasUlids;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'application_id',
        'type',
        'file_path',
    ];

    public function applications() {
        return $this->belongsTo(Application::class, 'application_id', 'user_id');
    }
}
