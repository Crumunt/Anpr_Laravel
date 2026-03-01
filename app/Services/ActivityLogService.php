<?php

namespace App\Services;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    /**
     * Activity type configurations for display
     */
    protected static array $activityConfig = [
        'account' => [
            'icon' => 'fa-user',
            'bgColor' => 'bg-blue-100',
            'iconColor' => 'text-blue-600',
        ],
        'application' => [
            'icon' => 'fa-file',
            'bgColor' => 'bg-green-100',
            'iconColor' => 'text-green-600',
        ],
        'approval' => [
            'icon' => 'fa-check',
            'bgColor' => 'bg-emerald-100',
            'iconColor' => 'text-emerald-600',
        ],
        'rejection' => [
            'icon' => 'fa-exclamation',
            'bgColor' => 'bg-red-100',
            'iconColor' => 'text-red-600',
        ],
        'document' => [
            'icon' => 'fa-file',
            'bgColor' => 'bg-purple-100',
            'iconColor' => 'text-purple-600',
        ],
        'vehicle' => [
            'icon' => 'fa-car',
            'bgColor' => 'bg-indigo-100',
            'iconColor' => 'text-indigo-600',
        ],
        'renewal' => [
            'icon' => 'fa-sync',
            'bgColor' => 'bg-cyan-100',
            'iconColor' => 'text-cyan-600',
        ],
        'login' => [
            'icon' => 'fa-door-open',
            'bgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
        ],
        'settings' => [
            'icon' => 'fa-cog',
            'bgColor' => 'bg-yellow-100',
            'iconColor' => 'text-yellow-600',
        ],
        'password' => [
            'icon' => 'fa-key',
            'bgColor' => 'bg-orange-100',
            'iconColor' => 'text-orange-600',
        ],
        'default' => [
            'icon' => 'fa-info',
            'bgColor' => 'bg-gray-100',
            'iconColor' => 'text-gray-600',
        ],
    ];

    /**
     * Log an activity for a user
     */
    public static function log(
        User $subject,
        string $description,
        string $event = 'default',
        ?User $causer = null,
        array $properties = []
    ): Activity {
        $activity = activity('user_activity')
            ->performedOn($subject)
            ->event($event)
            ->withProperties($properties);

        if ($causer) {
            $activity->causedBy($causer);
        } elseif (auth()->check()) {
            $activity->causedBy(auth()->user());
        }

        return $activity->log($description);
    }

    /**
     * Log account activation
     */
    public static function logAccountActivated(User $user, ?User $causer = null): Activity
    {
        return self::log($user, 'Account activated', 'account', $causer);
    }

    /**
     * Log account deactivation
     */
    public static function logAccountDeactivated(User $user, ?User $causer = null): Activity
    {
        return self::log($user, 'Account deactivated', 'account', $causer);
    }

    /**
     * Log password setup/change
     */
    public static function logPasswordSet(User $user): Activity
    {
        return self::log($user, 'Password set up successfully', 'password', $user);
    }

    /**
     * Log password changed
     */
    public static function logPasswordChanged(User $user): Activity
    {
        return self::log($user, 'Password changed', 'password', $user);
    }

    /**
     * Log application submitted
     */
    public static function logApplicationSubmitted(User $user, string $applicationType): Activity
    {
        return self::log(
            $user,
            "Submitted {$applicationType} application",
            'application',
            $user,
            ['applicant_type' => $applicationType]
        );
    }

    /**
     * Log application approved
     */
    public static function logApplicationApproved(User $user, ?User $admin = null): Activity
    {
        $adminName = $admin?->details?->full_name ?? $admin?->email ?? 'System';
        return self::log(
            $user,
            "Application approved by <strong>{$adminName}</strong>",
            'approval',
            $admin
        );
    }

    /**
     * Log application rejected
     */
    public static function logApplicationRejected(User $user, ?User $admin = null, ?string $reason = null): Activity
    {
        $adminName = $admin?->details?->full_name ?? $admin?->email ?? 'System';
        $description = "Application rejected by <strong>{$adminName}</strong>";
        if ($reason) {
            $description .= ": {$reason}";
        }
        return self::log($user, $description, 'rejection', $admin, ['reason' => $reason]);
    }

    /**
     * Log application removed/withdrawn
     */
    public static function logApplicationRemoved(User $user, ?User $causer = null): Activity
    {
        return self::log($user, 'Application removed', 'application', $causer);
    }

    /**
     * Log document uploaded
     */
    public static function logDocumentUploaded(User $user, string $documentType): Activity
    {
        return self::log(
            $user,
            "Uploaded document: {$documentType}",
            'document',
            $user,
            ['document_type' => $documentType]
        );
    }

    /**
     * Log vehicle registered
     */
    public static function logVehicleRegistered(User $user, string $plateNumber): Activity
    {
        return self::log(
            $user,
            "Registered vehicle: <strong>{$plateNumber}</strong>",
            'vehicle',
            $user,
            ['plate_number' => $plateNumber]
        );
    }

    /**
     * Log vehicle removed
     */
    public static function logVehicleRemoved(User $user, string $plateNumber, ?User $causer = null): Activity
    {
        return self::log(
            $user,
            "Vehicle removed: <strong>{$plateNumber}</strong>",
            'vehicle',
            $causer,
            ['plate_number' => $plateNumber]
        );
    }

    /**
     * Log profile updated
     */
    public static function logProfileUpdated(User $user, string $section = 'profile'): Activity
    {
        return self::log($user, "Updated {$section} information", 'settings', $user);
    }

    /**
     * Log user login
     */
    public static function logLogin(User $user): Activity
    {
        return self::log($user, 'Logged in', 'login', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log gate pass issued
     */
    public static function logGatePassIssued(User $user, string $gatePassNumber, ?User $admin = null): Activity
    {
        $adminName = $admin?->details?->full_name ?? $admin?->email ?? 'System';
        return self::log(
            $user,
            "Gate pass <strong>{$gatePassNumber}</strong> issued by <strong>{$adminName}</strong>",
            'approval',
            $admin,
            ['gate_pass_number' => $gatePassNumber]
        );
    }

    /**
     * Get activities for a user formatted for display
     */
    public static function getFormattedActivities(User $user, int $limit = 20): array
    {
        $activities = Activity::where('subject_type', User::class)
            ->where('subject_id', $user->id)
            ->where('log_name', 'user_activity')
            ->with('causer')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $activities->map(function ($activity) {
            $event = $activity->event ?? 'default';
            $config = self::$activityConfig[$event] ?? self::$activityConfig['default'];

            return [
                'description' => $activity->description,
                'timestamp' => $activity->created_at->diffForHumans(),
                'exact_time' => $activity->created_at->format('M d, Y g:i A'),
                'icon' => $config['icon'],
                'bgColor' => $config['bgColor'],
                'iconColor' => $config['iconColor'],
                'causer' => $activity->causer?->details?->full_name ?? $activity->causer?->email ?? null,
                'properties' => $activity->properties->toArray(),
            ];
        })->toArray();
    }
}
