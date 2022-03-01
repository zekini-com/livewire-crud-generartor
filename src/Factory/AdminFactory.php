<?php

namespace Zekini\CrudGenerator\Factory;

use App\Models\User;

class AdminFactory
{
    public static function create(?array $attributes = null): User
    {
        $user = User::factory()->create($attributes);

        collect(config('zekini-admin.admin_roles'))
            ->each(function ($role) use ($user) {
                $user->assignRole($role['name']);
            });

        return User::findOrFail($user->id);
    }
}
