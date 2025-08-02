<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    //
    public $incrementing = false;
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'clsu_id',
        'current_address',
        'street_address',
        'barangay',
        'city_municipality',
        'province',
        'postal_code',
        'country',
        'license_number',
        'college_unit_department',
        'approved_by',
        'status_id',
    ];


    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
    
    public function approvedBy() {
        return $this->belongsTo(UserDetails::class, 'approved_by', 'uuid');
    }
}
