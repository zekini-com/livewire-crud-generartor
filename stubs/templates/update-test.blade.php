@php echo "<?php";
@endphp

namespace Tests\Unit\{{$modelBaseName}};

use Tests\TestCase;
use App\Models\{{$modelBaseName}};
use App\Http\Livewire\Edit{{$modelBaseName}};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class {{$modelBaseName}}UpdateTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test we can create {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_update_{{strtolower($resource)}}()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.edit');

      $this->actingAs($admin, $guard);

      $this->faker = \Faker\Factory::create();
      $firstData = 'raw_data';

      $model = {{$modelBaseName}}::factory()->create();

      Livewire::test(Edit{{$modelBaseName}}::class, ['{{ strtolower($resource)}}'=> $model->id])
      @foreach($columnFakerMappings as $index=>$col)
        @if($index == 1)
        ->set('{{$col['name']}}', $firstData)
        @else
        ->set('{{$col['name']}}', {!!$col['faker']!!})
        @endif
      @endforeach
        ->call('update');

        $this->assertTrue({{ucfirst($modelBaseName)}}::where('{{$columnFakerMappings->first()['name']}}', $firstData)->exists());
       
    }

    
    /**
     * Test Required field
     *
     * @return void
     */
    public function test_{{$columnFakerMappings->first()['name']}}_is_required()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = {{$adminModel}}::factory()->create();
        $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.edit');
  
        $this->actingAs($admin, $guard);
        $this->faker = \Faker\Factory::create();
        $model = {{$modelBaseName}}::factory()->create();

      Livewire::test(Edit{{$modelBaseName}}::class, ['{{ strtolower($resource)}}'=> $model->id])
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('{{$col['name']}}', '')
          @else
          ->set('{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
          ->call('update')
          ->assertHasErrors(['{{$columnFakerMappings->first()['name']}}'=> 'required']);
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
  
        $this->actingAs($admin, $guard);
        $this->faker = \Faker\Factory::create();
        $model = {{$modelBaseName}}::factory()->create();

      Livewire::test(Edit{{$modelBaseName}}::class, ['{{ strtolower($resource)}}'=> $model->id])
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('{{$col['name']}}', '')
          @else
          ->set('{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
          ->call('update')
          ->assertForbidden();
    }





}
