<?php

use Yormy\LaravelFootsteps\Enums\LogType;

return [
    'enabled' => true,

    'log_model' => 'Yormy\LaravelFootsteps\Models\Log',

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

    /*
     * List the exceptions you want to log with the client
     * or leave empty to log all ?
     */
    'log_exceptions' => [
        'enabled' => true,
        'exceptions' => [
            'Illuminate\Database\Eloquent\ModelNotFoundException' =>'MODEL_NOT_FOUND',
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' => LogType::EXCEPTION_PAGE_NOT_FOUND->value,
        ]
    ],

    'log_visits' => [
        'routes_include' => [
            '*',        // * to include all routes
        ],
        'routes_exclude' => [
            '*debugbar*',       // always exclude these routes, even if they are in the include list
        ],
        'urls_include' => [
            '*',        // * to include all urls
        ],
        'urls_exclude' => [
            '*user-activity*',
            '*telescope*',
            '*_debugbar*',
        ],

    ],

    'content' => [
        'ip' => true,
        'user_agent' => true,
        'duration' => true,
        'geoip' => true,
        'payload' => [
            'enabled' => true,
            'max_characters' => 200,
        ],
        'response' => [
            'enabled' => true,
            'max_characters' => 200,
        ],

        // do not log the following keys in the database
        'blacklisted_keys' => [
            'id',
            'password',
            'title',
        ],

        'model' => [
            'values_changed' => true,
            'values_old' => true,
        ]
    ],


    // When the clean-up command is run, delete old logs greater than `purge` days
    'prune_logs_after_days' => 1,
];
