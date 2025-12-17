<?php

namespace App\Models;

use App\ApplicantType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    use HasUlids;

    protected $keyType = "string";
    public $incrementing = false;

    protected $casts = [
        "applicant_type" => ApplicantType::class,
    ];

    protected $fillable = [
        "user_id",
        "applicant_type",
        "approved_by",
        "status_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function documents()
    {
        return $this->hasMany(Documents::class, "application_id", "id");
    }
}
