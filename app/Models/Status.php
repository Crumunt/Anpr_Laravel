<?php

namespace App\Models;

use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $fillable = ["type", "code", "status_name", "description"];

    public function applications()
    {
        return $this->hasMany(UserDetails::class, "status_id");
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, "status_id");
    }

    protected const VEHICLE_PENDING = "pending_verification";

    public static function vehiclePending()
    {
        return self::where("type", "vehicle")
            ->where("code", self::VEHICLE_PENDING)
            ->first();
    }

    protected const APPLICATION_STATUS = "under_review";

    public static function applicationPending()
    {
        return self::where("type", "application")
            ->where("code", self::APPLICATION_STATUS)
            ->first();
    }
}
