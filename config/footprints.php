<?php

use Yormy\FootprintsLaravel\Enums\LogType;

return [
    /*
     * If set to false, nothing will be saved to the database.
     */
    'enabled' => env('FOOTPRINTS_LOGGER_ENABLED', true),

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Log model shipped with this package.
     */
    'table_name' => 'footprints',

    /*
     * This model will be used to log footprints.
     * It should implement the Yormy\FootprintsLaravel\Interfaces\FootprintInterface interface
     * and extend Illuminate\Database\Eloquent\Model.
     */
    'log_model' => Yormy\FootprintsLaravel\Models\Footprint::class,

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
     * specify the exception to handle with the key to use (either aLogType value or a string "Your Exception Name Indicator"
     */
    'log_exceptions' => [
        'enabled' => true,
        'exceptions' => [
            'Illuminate\Database\Eloquent\ModelNotFoundException' => LogType::EXCEPTION_MODEL_NOT_FOUND->value,
            'Illuminate\Http\Exceptions\ThrottleRequestsException' => LogType::EXCEPTION_TOO_MANY_REQUEST->value,
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' => LogType::EXCEPTION_PAGE_NOT_FOUND->value,
        ],
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
        'geoip' => false,
        'payload' => [
            'enabled' => true,
            'max_characters_per_field' => 50,
        ],
        'response' => [
            'enabled' => true,
            'max_characters' => 200,
        ],

        // do not log the following keys in the database
        'blacklisted_keys' => [
            '_token',
            'password',
            'new_password',
            'one_time_password'
        ],

        'model' => [
            'values_changed' => true,
            'values_old' => true,
        ],
    ],

    /*
     * When the clean-command is executed, all recording footprints older than
     * the number of days specified here will be deleted.
     * The clean command is automatically run every day
     */
    'delete_records_older_than_days' => 1,

    'resolvers' => [
        'user' => \Yormy\FootprintsLaravel\Services\Resolvers\UserResolver::class,
        'impersonator' => \Yormy\FootprintsLaravel\Services\Resolvers\ImpersonatorResolver::class,
    ],

    'cookies' => [
        'login_session_id' => 'fpsid', // tracking the steps of the user accross tabs
        'impersonator_id' => 'iid',

        // Your frontend can set a cookie that identifies uniquely the browser
        // this cookie will then also be stored in the database
        // you can name the cookie anything you like
        'browser_fingerprint' => 'browser_fingerprint',

        // custom cookie logging
        'custom' => [
        ],
        'max_characters' => 100,
    ]
];
