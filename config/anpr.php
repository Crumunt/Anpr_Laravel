<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ANPR Dashboard Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration values for the ANPR Dashboard. These are used for
    | temporary/mock values until production features are implemented.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Simulated Camera Settings
    |--------------------------------------------------------------------------
    |
    | Since the testing camera is not IP-based, these placeholder values
    | simulate active camera status. Replace with real IP-based camera
    | monitoring when available.
    |
    */
    'cameras' => [
        'active_count' => env('ANPR_ACTIVE_CAMERAS', 3),
        'total_count' => env('ANPR_TOTAL_CAMERAS', 4),

        // Future: List of IP cameras for status monitoring
        'ip_cameras' => [
            // Example structure for future implementation:
            // [
            //     'id' => 'cam-001',
            //     'name' => 'Main Gate Camera',
            //     'ip' => '192.168.1.100',
            //     'port' => 554,
            //     'gate_id' => 'main-gate',
            // ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gate Configuration
    |--------------------------------------------------------------------------
    |
    | Available gates for filtering. Add more gates as needed.
    |
    */
    'gates' => [
        'main-gate' => 'Main Gate',
        'back-gate' => 'Back Gate',
        'service-gate' => 'Service Gate',
    ],

    /*
    |--------------------------------------------------------------------------
    | Flagged Plate Simulation (Temporary)
    |--------------------------------------------------------------------------
    |
    | Until the `is_flagged` column is added to the database, these settings
    | control simulated flagging behavior. Set `simulation_enabled` to false
    | once the real database column is in place.
    |
    */
    'flagging' => [
        // Set to false when real is_flagged column is available
        'simulation_enabled' => env('ANPR_FLAG_SIMULATION', true),

        // Percentage of records to randomly flag in simulation mode (0-100)
        'simulation_percentage' => env('ANPR_FLAG_SIMULATION_PERCENT', 5),

        // Session key for storing temporary flag states
        'session_key' => 'anpr_flagged_records',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the dashboard display and data queries.
    |
    */
    'dashboard' => [
        // Number of hours to look back for metrics
        'metrics_hours' => 24,

        // Number of records per page in the recent vehicles table
        'records_per_page' => 15,

        // Auto-refresh interval in seconds (0 to disable)
        'auto_refresh_interval' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert Thresholds
    |--------------------------------------------------------------------------
    |
    | Thresholds for generating alerts based on detection metrics.
    |
    */
    'alerts' => [
        // Minimum confidence score to consider a detection valid
        'min_confidence' => 0.75,

        // Number of flagged detections before triggering critical alert
        'flagged_critical_threshold' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Gate Pass Validity Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for gate pass validity periods and renewal options.
    |
    */
    'gate_pass' => [
        // Default validity period in years for new gate passes
        'default_validity_years' => env('GATE_PASS_VALIDITY_YEARS', 4),

        // Number of days before expiration to show renewal warning
        'renewal_warning_days' => env('GATE_PASS_RENEWAL_WARNING_DAYS', 90),

        // Available validity period options (in years) for admin selection
        'validity_options' => [1, 2, 3, 4, 5],

        // Allow applicants to request renewal before expiration
        'allow_early_renewal' => true,
    ],
];
