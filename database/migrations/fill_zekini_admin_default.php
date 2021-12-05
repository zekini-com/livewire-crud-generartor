<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class FillZekiniAdminDefault extends Migration
{

    public function __construct()
    {
        // TODO read this from an admin configuration file
        $this->guard = config('zekini-admin.defaults.guard');
        $this->className = Zekini\CrudGenerator\Models\ZekiniAdmin::class;

        $this->permissions = collect([
            // manage users (access)
            'administer.user.index',
            'administer.user.create',
            'administer.user.edit',
            'administer.user.delete',
        ]);
        $this->roles = collect([[
            'name'=> 'Administrator',
            'permissions'=> $this->permissions->toArray()]
        ]);

        $this->admin = [
            'name'=> 'Administrator',
            'email'=> 'support@zekini.com',
            'email_verified_at'=> now(),
            'password'=> Hash::make('localpassword@zekini')
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
        foreach($roles as $role) {
            $role = (array)$role;
            $modelRole = [
                'model_id'=> $adminId,
                'model_type'=> $this->className,
                'role_id'=> $role['id']
            ];
            // we check if role exists
            if (! DB::table('model_has_roles')->where($modelRole)->exists()) {
               DB::table('model_has_roles')->insert($modelRole);
            }
        }

        // setup admin permissions
        foreach($permissions as $rolePermission) {
            $rolePermission  = (array)$rolePermission;
            $modelPermission = [
                'model_id'=> $adminId,
                'model_type'=> $this->className,
                'permission_id'=> $rolePermission['id']
            ];
            // we check if role exists
            if (! DB::table('model_has_permissions')->where($modelPermission)->exists()) {
                DB::table('model_has_permissions')->insert($modelPermission);
             }
        }
    }

    
    /**
     * The role admin would be assuming
     *
     * @return array
     */
    protected function setupRoles()
    {
        foreach($this->roles as $role) {
            // we check if role exists
           
            if (! DB::table('roles')->where(['name'=>$role['name'],'guard_name'=>$this->guard])->exists()) {

                $roleId = DB::table('roles')->insertGetId([
                    'name'=> $role['name'],
                    'guard_name'=> $this->guard,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                ]);
            }

            if(! $roleId) $roleId = DB::table('roles')->where(['name'=>$role['name'],'guard_name'=>$this->guard])->first()->id;

            // map role to permisssions
            foreach($role['permissions'] as $rolePermission) {
                $permission = DB::table('permissions')->where(['name'=> $rolePermission, 'guard_name'=> $this->guard])->first();
                $rolePermission = [
                    'permission_id'=> $permission->id,
                    'role_id'=> $roleId
                ];
                DB::table('role_has_permissions')->insert($rolePermission);
            }
        }
    }
    
    /**
     * Setup permissions
     *
     * @return void
     */
    protected function setupPermissions()
    {
        foreach($this->permissions as $permission) {
            // we check if permission exists
            if (! DB::table('permissions')->where(['name'=>$permission, 'guard_name'=>$this->guard])->exists()) {

                DB::table('permissions')->insert([
                    'name'=> $permission,
                    'guard_name'=> $this->guard,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                ]);
            }
        }
        
    }
    
   
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function(){

            // Setup Permissions
            $this->setupPermissions();

            // Setup Role
            $this->setupRoles();

            // Setup Default Admin Role
            $this->setupSuperAdmin();

        });
        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function(){

            $this->clearSuperAdmin();

            $this->clearRoles();

            $this->clearPermissions();
        });

        app()['cache']->forget(config('permission.cache.key'));
    }

    
    /**
     * clearSuperAdmin
     *
     * @return void
     */
    protected function clearSuperAdmin()
    {
        $admin = DB::table('zekini_admins')->where('email', $this->admin['email'])->first();
        $adminId = $admin->id;

        $modelAttachments = [
            'model_id'=> $adminId,
            'model_type'=> $this->className
        ];

        // clear all roles for this user
        DB::table('model_has_roles')->where($modelAttachments)->delete();

        // clear all permissions for this user
        DB::table('model_has_permissions')->where($modelAttachments)->delete();
    }
    
    /**
     * clearRoles
     *
     * @return void
     */
    protected function clearRoles()
    {
        $roles  = $this->roles->map(function($role){ return $role['name']; })->toArray();
        DB::table('roles')->whereIn('name', $roles)->delete();
    }
    
    /**
     * clearPermissions
     *
     * @return void
     */
    protected function clearPermissions()
    {
        $permissions  = $this->permissions->toArray();
        DB::table('roles')->whereIn('name', $permissions)->delete();
    }
}
