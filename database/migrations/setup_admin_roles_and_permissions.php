<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\Collection;
use Zekini\CrudGenerator\Traits\CreatesPermissionObject;

class SetupAdminRolesAndPermissions extends Migration
{
    use CreatesPermissionObject;

    protected string $guardName;

    protected Config $config;

    protected Collection $permissions;

    protected Collection $roles;


    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->guardName = $this->config->get('zekini-admin.defaults.guard', 'web');

        $this->permissions = new Collection($this->config->get('zekini-admin.permissions', []));

        $this->roles = new Collection($this->config->get('zekini-admin.admin_roles', []));
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

            if (!$this->ObjectExists($role['name'], 'roles')) {

                $roleId = $this->createObject($role['name'], 'roles');
            }

            if (!$roleId) $roleId =  $this->getObject($role['name'], 'roles')->id;
            

            // map role to permisssions
            $role->syncPermissions($this->getRolePermissions($role));
           
        }
    }

    protected function getRolePermissions($role): array
    {
        return $role['permissions'][0] === '*' ? $this->permissions->toArray() : $role['permissions'];
    }

   
    public function up()
    {
        DB::transaction(function () {

            // Setup Permissions
            $this->setupPermissions();

            // Setup Role
            $this->setupRoles();
        });

        app()['cache']->forget(config('permission.cache.key'));
    }

    public function down()
    {
        DB::transaction(function () {

            $this->clearRoles();

            $this->clearPermissions();
        });

        app()['cache']->forget(config('permission.cache.key'));
    }


    protected function clearRoles(): void
    {
        $roles  = $this->roles->pluck('name')->toArray();

        $this->deleteObjects('roles', $roles);
    }

    protected function clearPermissions(): void
    {
        $permissions  = $this->permissions->pluck('name')->toArray();

        $this->deleteObjects('permissions', $permissions);
        
    }

    
}
