@php echo "<?php";
@endphp

namespace Tests\Unit\{{$modelBaseName}};

use Tests\TestCase;
use App\Models\{{$modelBaseName}};
use App\Http\Livewire\List{{$modelBaseName}};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class {{$modelBaseName}}ListTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test we can create {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_destroy_{{strtolower($resource)}}()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($resource)}}.delete');
      $admin->givePermissionTo('admin.{{ strtolower($resource)}}.index');
      $this->actingAs($admin, $guard);

      $this->faker = \Faker\Factory::create();
      $firstData = 'raw_data';

      $model = {{$modelBaseName}}::factory()->create();

      Livewire::test(List{{$modelBaseName}}::class)
        ->call('delete', $model->id);

        $this->assertFalse({{ucfirst($modelBaseName)}}::where('id', $model->id)->exists());
       
    }



    /**
     * Test Access Forbidden
     *
     * @return void
     */
    public function test_access_is_forbidden()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = {{$adminModel}}::factory()->create();

        $admin->givePermissionTo('admin.{{ strtolower($resource)}}.index');
  
        $this->actingAs($admin, $guard);
        $this->faker = \Faker\Factory::create();
        $model = {{$modelBaseName}}::factory()->create();

      Livewire::test(List{{$modelBaseName}}::class)
          ->call('delete', $model->id)
          ->assertForbidden();
    }





}
