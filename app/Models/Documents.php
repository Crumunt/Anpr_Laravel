<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'document_name',
        'document_path',
    ];
}
