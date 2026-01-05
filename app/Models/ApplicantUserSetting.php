<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantUserSetting extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $fillable = [
        'id',
        'user_id',
        'two_factor_enabled',
        'account_privacy',
        'gate_pass_renewal_reminders',
        'entry_exit_notifications',
        'maintenance_alerts',
        'emergency_notifications',
        'sms_notifications',
        'email_notifications',
        'preferred_contact_method',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_email',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'gate_pass_renewal_reminders' => 'boolean',
        'entry_exit_notifications' => 'boolean',
        'maintenance_alerts' => 'boolean',
        'emergency_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'email_notifications' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
