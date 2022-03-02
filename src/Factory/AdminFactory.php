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
        $role = collect(config('zekini-admin.defaults.role'));
        $user  = User::factory()->create($attributes);

        $user->assignRole($role);

        return $user;

    }

   
}
