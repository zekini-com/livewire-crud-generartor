<?php

return [

    'defaults' => [
        'guard' => 'zekini_admin',
        'passwords' => 'zekini_admins',

        // used for development
        'default-email' => 'support@zekini.com',
        'default-password' => 'password'
    ],

    'guards' => [
        'zekini_admin' => [
            'driver' => 'session',
            'provider' => 'zekini_admins'
        ]
    ],

    'providers' => [
        'zekini_admins' => [
            'driver' => 'eloquent',
            'model' => Zekini\CrudGenerator\Models\ZekiniAdmin::class
        ]
    ],

    'passwords' => [
        'zekini_admins' => [
            'provider' => 'zekini_admins',
            'table' => 'zekini_admin_password_resets',
            'expire' => 60,
            'throttle' => 60
        ]
        ],

    'auth_routes' => [
        'login_redirect' => '/admin',
        'logout_redirect' => '/admin/login',
        'password_reset_redirect' => '/admin/login'
    ],

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Here is we register the relationship linkings between models
    |  supported relationship types : has_many, has_one, belongsTo, belongsToMany
    | Eg  'posts' => [
    |       [
    |            'name' => 'has_many',
    |            'table' => 'comments',
    |            'record_title' => 'comment'
    |        ]
    |        
    |    ],
    | Search Keys 
    |  Column name to use for serach
    | 'post' => 'title'
    |
    */

    'relationships' => [

        'roles' => [
            [
                'name' => 'belongs_to_many',
                'table' => 'permissions',
                'record_title' => 'name',
                'pivot' => 'role_has_permissions',
                'foreign_pivot_key' => 'role_id',
                'related_pivot_key' => 'permission_id'
            ]
        ],

        'permissions' => [
            [
                'name' => 'belongs_to_many',
                'table' => 'roles',
                'record_title' => 'name',
                'pivot' => 'role_has_permissions',
                'foreign_pivot_key' => 'permission_id',
                'related_pivot_key' => 'role_id'
            ]
        ],
        
        'zekini_admins' => [
            [
                'name' => 'belongs_to_many',
                'table' => 'roles',
                'record_title' => 'name',
                'pivot' => 'model_has_roles',
                'foreign_pivot_key' => 'model_id',
                'related_pivot_key' => 'role_id'
            ]
        ]
    ],

    'search_keys' => [

    ]
];