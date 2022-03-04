@php echo "<?php";
@endphp

namespace Tests\Unit\{{$modelBaseName}};
@php($datatableComponent = ucfirst(Str::plural($modelBaseName)).'Table')

use Tests\TestCase;
use App\Models\{{$modelBaseName}};
use App\Http\Livewire\{{ucfirst(Str::plural($modelBaseName))}}\Datatable\{{$datatableComponent}};
use Livewire\Livewire;
use App\Imports\{{Str::plural(ucfirst($modelBaseName))}}Import;
use Maatwebsite\Excel\Facades\Excel;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class {{$modelBaseName}}DatatableTest extends TestCase
{
    /**
     * Test we can create {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_destroy_{{strtolower($resource)}}()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.delete');
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.index');
      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';

      $model = {{$modelBaseName}}::factory()->create();

      Livewire::test({{$datatableComponent}}::class)
        ->call('delete', $model->id);

        $this->assertFalse({{ucfirst($modelBaseName)}}::find($model->id)->exists());
       
    }



    /**
     * Test Access Forbidden
     *
     * @return void
     */
    public function test_access_is_forbidden()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = AdminFactory::create();

        // by default admin has all permissions 
        $role =  Role::findByName(config('zekini-admin.defaults.role'));
        $role->revokePermissionTo('admin.{{ strtolower($modelDotNotation)}}.delete');
        
  
        $this->actingAs($admin, $guard);

        $model = {{$modelBaseName}}::factory()->create();

      Livewire::test({{$datatableComponent}}::class)
          ->call('forceDelete', $model->id)
          ->assertForbidden();
    }

    /**
     * Restore sofdelete
     *
     * @return void
     */
    public function test_we_can_restore_soft_deletes()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = AdminFactory::create();

        $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.delete');
  
        $this->actingAs($admin, $guard);

        $model = {{$modelBaseName}}::factory()->create();

        $model->delete();

      Livewire::test({{$datatableComponent}}::class)
          ->call('restore', $model->id);
    
          $this->assertTrue({{ucfirst($modelBaseName)}}::find($model->id)->exists());
    }

    


   




}
