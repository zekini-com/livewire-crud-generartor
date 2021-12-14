<?php

return [

    'defaults'=> [
        'guard'=> 'zekini_admin',
        'passwords'=> 'zekini_admins'
    ],

    'guards'=> [
        'zekini_admin'=> [
            'driver'=> 'session',
            'provider'=> 'zekini_admins'
        ]
    ],

    'providers'=> [
        'zekini_admins'=> [
            'driver'=> 'eloquent',
            'model'=> Zekini\CrudGenerator\Models\ZekiniAdmin::class
        ]
    ],

    'passwords'=> [
        'zekini_admins'=> [
            'provider'=> 'zekini_admins',
            'table'=> 'zekini_admin_password_resets',
            'expire'=> 60,
            'throttle'=> 60
        ]
        ],

    'auth_routes'=> [
        'login_redirect'=> '/admin',
        'logout_redirect'=> '/admin/login',
        'password_reset_redirect'=> '/admin/login'
    ],

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Here is we register the relationship linkings between models
    |  supported relationship types : has_many, has_one, belongsTo, belongsToMany
    | Eg  'posts'=> [
    |       [
    |            'name'=> 'has_many',
    |            'table'=> 'comments',
    |            'record_title'=> 'comment'
    |        ]
    |        
    |    ],
    |
    */

    'relationships'=> [
       
    ]
];