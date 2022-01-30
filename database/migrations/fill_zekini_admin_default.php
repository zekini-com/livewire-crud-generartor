<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class FillZekiniAdminDefault extends Migration
{
    protected $guardName;

    public function __construct()
    {
        $this->guardName = config('zekini-admin.defaults.guard', 'zekini_admin');
        $this->className = Zekini\CrudGenerator\Models\ZekiniAdmin::class;

        $this->permissions = collect([
            // manage users (access)
            'administer.user.index',
            'administer.user.create',
            'administer.user.edit',
            'administer.user.delete',
        ]);

        $this->roles = collect([
            [
                'name' => 'Administrator',
                'permissions' => $this->permissions->toArray()
            ]
        ]);

        $this->admin = [
            'name' => 'Administrator',
            'email' => config('zekini-admin.defaults.default-email'),
            'email_verified_at' => now(),
            'password' => Hash::make(config('zekini-admin.defaults.default-password'))
        ];
    }

    /**
     * Setup Super Admin with all roles and all permissions
     *
     * @return void
     */
    protected function setupSuperAdmin()
    {
        // create admin
        $adminId = DB::table('zekini_admins')->insertGetId($this->admin);

        $roles = DB::table('roles')->get();

        $permissions = DB::table('permissions')->get();

        // setup admin roles
        foreach ($roles as $role) {
            $role = (array)$role;
            $modelRole = [
                'model_id' => $adminId,
                'model_type' => $this->className,
                'role_id' => $role['id']
            ];
            // we check if role exists
            if (!DB::table('model_has_roles')->where($modelRole)->exists()) {
                DB::table('model_has_roles')->insert($modelRole);
            }
        }

        // setup admin permissions
        foreach ($permissions as $rolePermission) {
            $rolePermission  = (array)$rolePermission;
            $modelPermission = [
                'model_id' => $adminId,
                'model_type' => $this->className,
                'permission_id' => $rolePermission['id']
            ];

            // we check if role exists
            if (!DB::table('model_has_permissions')->where($modelPermission)->exists()) {
                DB::table('model_has_permissions')->insert($modelPermission);
            }
        }
    }

    /**
     * The role admin would be assuming
     *
     * @return void
     */
    protected function setupRoles()
    {
        foreach ($this->roles as $role) {
            // we check if role exists

            $roleId = null;

            if (!DB::table('roles')
                ->where([
                    'name' => $role['name'],
                    'guard_name' => $this->guardName
                ])
                ->exists()) {

                $roleId = DB::table('roles')
                    ->insertGetId([
                        'name' => $role['name'],
                        'guard_name' => $this->guardName,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
            }

            if (!$roleId) $roleId = DB::table('roles')
                ->where([
                    'name' => $role['name'],
                    'guard_name' => $this->guardName
                ])
                ->first()->id;

            // map role to permisssions
            foreach ($role['permissions'] as $rolePermission) {
                $permission = DB::table('permissions')
                    ->where([
                        'name' => $rolePermission,
                        'guard_name' => $this->guardName
                    ])
                    ->first();

                $rolePermission = [
                    'permission_id' => $permission->id,
                    'role_id' => $roleId
                ];

                DB::table('role_has_permissions')
                    ->insert($rolePermission);
            }
        }
    }

    protected function setupPermissions(): void
    {
        foreach ($this->permissions as $permission) {
            // we check if permission exists
            if (!DB::table('permissions')
                ->where([
                    'name' => $permission,
                    'guard_name' => $this->guardName
                ])
                ->exists()) {

                DB::table('permissions')->insert([
                    'name' => $permission,
                    'guard_name' => $this->guardName,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

    public function up()
    {
        DB::transaction(function () {

            // Setup Permissions
            $this->setupPermissions();

            // Setup Role
            $this->setupRoles();

            // Setup Default Admin Role
            $this->setupSuperAdmin();
        });

        app()['cache']->forget(config('permission.cache.key'));
    }

    public function down()
    {
        DB::transaction(function () {

            $this->clearSuperAdmin();

            $this->clearRoles();

            $this->clearPermissions();
        });

        app()['cache']->forget(config('permission.cache.key'));
    }

    protected function clearSuperAdmin(): void
    {
        $admin = DB::table('zekini_admins')
            ->where('email', $this->admin['email'])
            ->first();

        $adminId = $admin->id;

        $modelAttachments = [
            'model_id' => $adminId,
            'model_type' => $this->className
        ];

        // clear all roles for this user
        DB::table('model_has_roles')
            ->where($modelAttachments)
            ->delete();

        // clear all permissions for this user
        DB::table('model_has_permissions')
            ->where($modelAttachments)
            ->delete();
    }

    protected function clearRoles(): void
    {
        $roles  = $this->roles->pluck('name')->toArray();
        DB::table('roles')
            ->whereIn('name', $roles)
            ->delete();
    }

    protected function clearPermissions(): void
    {
        $permissions  = $this->permissions->pluck('name')->toArray();
        DB::table('roles')
            ->whereIn('name', $permissions)
            ->delete();
    }
}
