<?php

namespace App\Models;

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
}
