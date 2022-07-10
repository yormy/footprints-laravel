<?php

return [
    'ignore_properties' => [
        'id',
        'xid',
        'created_at',
        'updated_at',
        'created_at_human',
        'created_at_local',
        'updated_at_local',
        'updated_at_human',
        'password'
    ],


    /*
    |--------------------------------------------------------------------------
    | Display
    |--------------------------------------------------------------------------
    | empty_attributes : when an attribute value has not been changed, how should it show up on the screen
    | html_allowed : allow which tags for the display of the title and description
    */
    'display' => [
        'empty_attribute' => '-',
        'html_allowed' => 'br,strong,em,s,p,ul,li,h1,h2,ins,del',
    ],


    'models' => [
        'user' => [
            'class' => Mexion\BedrockUsers\Models\Member::class,
            'key' => 'id',
            'display_name' => 'name',
        ]
    ]
];
