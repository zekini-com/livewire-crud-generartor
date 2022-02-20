<?php

return [

    'defaults'=> [
        'guard'=> 'web',
        'role'=> 'admin'
    ],

    'admin_roles'=> [

        [
            'name'=> 'admin',
            'permissions'=> ['*']
        ]
    ],

    'permissions'=> [
        'administer.user.index',
        'administer.user.create',
        'administer.user.edit',
        'administer.user.delete',
    ],


    'auth_routes' => [
        'login_redirect' => '/admin',
        'logout_redirect' => '/admin/login'
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
    |  Column name to use for search
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

        'users' => [
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

    'search_keys' => [],

    'version' => '1.0.1',
];
