<?php

namespace Zekini\CrudGenerator\Factory;

use App\Models\User;

class AdminFactory 
{


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public static function create($attributes=null): User
    {
        $roles = collect(config('zekini-admin.admin_roles'));
        $user  = User::factory()->create($attributes);

        foreach($roles as $role)
        {
            $user->assignRole($role['name']);
        }

        return $user;

    }

   
}
