<?php

return [

    'defaults'=> [
        'guard'=> 'zekini-admin'
    ],

    'guards'=> [
        'zekini-admin'=> [
            'driver'=> 'session',
            'provider'=> 'zekini-admins'
        ]
    ],

    'providers'=> [
        'zekini-admins'=> [
            'driver'=> 'eloquent',
            'model'=> Zekini\CrudGenerator\Models\ZekiniAdmin::class
        ]
    ],

    'passwords'=> [
        'zekini-admins'=> [
            'provider'=> 'zekini-admins',
            'table'=> 'zekini_admin_password_resets',
            'expire'=> 60,
            'throttle'=> 60
        ]
        ],

    'auth_routes'=> [
        'login_redirect'=> '/admin',
        'logout_redirect'=> '/admin/login'
    ]
];