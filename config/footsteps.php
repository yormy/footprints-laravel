<?php

return [
    'enabled' => true,

    /*
     activated => enabled
     */

    'activated' => true, // active/inactive all logging
    'middleware' => ['web', 'auth'],
    'route_path' => 'admin/user-activity',
    'admin_panel_path' => 'admin/dashboard',
    'delete_limit' => 7, // default 7 days

    'log_model' => 'Yormy\LaravelFootsteps\Models\Log',

    'model' => [
        'user' => 'App\Models\User',
    ],

    'log_events' => [
        'model_created' => true,
        'model_updated' => true,
        'model_deleted' => true,
        'auth_login' => true,
        'auth_logout' => true,
        'auth_lockout' => true,
        'route_visit' => true,
        'on_custom' => true,
        'auth_failed' => true,
        'auth_other_device_logout' => true,
    ],

    'ignore_routes' => [
        '*debugbar*',
    ],

    'ignore_urls' => [
        '*user-activity*',
        '*telescope*',
        '*_debugbar*'
    ],

    'log_response' => [
        'enabled' => true,
        'max_characters' => 200,
    ],

    // do not log the following keys in the database
    'blacklisted_keys' => [
        'id',
        'password',
        'title',
    ],

    'log_geoip' => true,

    // When the clean-up command is run, delete old logs greater than `purge` days
    'prune_logs_after_days' => 1,

    /*
     * List the exceptions you want to log with the client
     * or leave empty to log all ?
     */
    'log_exceptions' => [
        'enabled' => true,
        'exceptions' => [
            'Illuminate\Database\Eloquent\ModelNotFoundException',
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
        ]
    ]
];
