<?php

return [
    'enabled' => true,

    /*
     activated => enabled
     */


    'activated'        => true, // active/inactive all logging
    'middleware'       => ['web', 'auth'],
    'route_path'       => 'admin/user-activity',
    'admin_panel_path' => 'admin/dashboard',
    'delete_limit'     => 7, // default 7 days

    'log_model' => 'Yormy\LaravelFootsteps\Models\Log',

    'model' => [
        'user' => 'App\Models\User'
    ],

    'log_events' => [
        'on_create'     => true,
        'on_update'       => true,
        'on_delete'     => true,
        'on_login'      => true,
        'on_logout'     => true,
        'on_lockout'    => true,
        'on_route'      => true,
        'on_custom'      => true,
    ],

    'ignore_routes' => [
        '*debugbar*'
    ],

    'ignore_urls' => [
        '*user-activity*'
    ],

    'log_response' => [
        'enabled' => true,
        'max_characters' => 200,
    ],

    // do not log the following keys in the database
    'blacklisted_keys' => [
        'id',
        'password',
        'title'
    ],

    'log_geoip' => true,

    'prune_logs_after_days' => 1,
];
