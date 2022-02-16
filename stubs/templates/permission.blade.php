@php echo "<?php"
@endphp


use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class {{ $className }} extends Migration
{
    protected $roles;

    protected $guardName;

    protected $className;

    /**
     * {{ $className }} constructor.
     */
    public function __construct()
    {
        $this->guardName = 'zekini_admin';
        $this->className = Zekini\CrudGenerator\Models\ZekiniAdmin::class;
        $this->permissions = collect([
            'admin.{{ $modelDotNotation }}',
            'admin.{{ $modelDotNotation }}.index',
            'admin.{{ $modelDotNotation }}.create',
            'admin.{{ $modelDotNotation }}.show',
            'admin.{{ $modelDotNotation }}.edit',
            'admin.{{ $modelDotNotation }}.delete'
        ]);

        //Role should already exists
        $this->roles = [
            [
                'name' => 'Administrator',
                'guard_name' => $this->guardName,
                'permissions' => $this->permissions->toArray(),
            ],
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
        $adminId = DB::table('zekini_admins')->where('name', 'Administrator')->first()->id;

        $roles = DB::table('roles')->get();
      
        $permissions = DB::table('permissions')->get();

        // setup admin roles
        foreach($roles as $role) {
            $role = (array)$role;
            $modelRole = [
                'model_id' => $adminId,
                'model_type' => $this->className,
                'role_id' => $role['id']
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
                'model_id' => $adminId,
                'model_type' => $this->className,
                'permission_id' => $rolePermission['id']
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
     * @return void
     */
    protected function attachPermissiontoAdminRole(): void
    {
        $role = DB::table('roles')->where('name', 'Administrator')->first();
        $roleId = $role->id;

        // map role to permisssions
        foreach($this->permissions as $rolePermission) {
            $permission = DB::table('permissions')
                ->where([
                    'name' => $rolePermission,
                    'guard_name' => $this->guardName,
                ])
                ->first();

            $rolePermission = [
                'permission_id' => $permission->id,
                'role_id' => $roleId
            ];

            // we check if role exists
            if (! DB::table('role_has_permissions')->where($rolePermission)->exists()) {
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
            if (! DB::table('permissions')
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

    /**
     * Run the migrations.
     *
     * {{'@'}}return void
     */
    public function up(): void
    {
        DB::transaction(function(){

            // Setup Permissions
            $this->setupPermissions();

            $this->attachPermissiontoAdminRole();
            

            // Setup Default Admin Role
            $this->setupSuperAdmin();

        });
        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * {{'@'}}return void
     */
    public function down(): void
    {
        DB::transaction(function(){

            $this->clearRoleHasPermissions();

            $this->clearPermissions();
        });
        
        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * clearRoleHasPermissions
     *
     * @return void
     */
    protected function clearRoleHasPermissions()
    {
        $permissions  = DB::table('permissions')->whereIn('name', $this->permissions->toArray())->get();
        $permissions = $permissions->pluck('id')->toArray();
        DB::table('role_has_permissions')->whereIn('permission_id', $permissions)->delete();
    }
    
    /**
     * clearPermissions
     *
     * @return void
     */
    protected function clearPermissions()
    {
        $permissions  = DB::table('permissions')->whereIn('name', $this->permissions->toArray())->delete();
    }
}
