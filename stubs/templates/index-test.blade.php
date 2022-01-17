@php echo "<?php";
@endphp

namespace Tests\Unit\{{$modelBaseName}};
@php($datatableComponent = ucfirst(Str::plural($modelBaseName)))
use Tests\TestCase;
use App\Models\{{$modelBaseName}};
use App\Http\Livewire\Create{{$modelBaseName}};
use App\Http\Livewire\{{ucfirst(Str::plural($modelBaseName))}}\{{$datatableComponent}};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class {{$modelBaseName}}Test extends TestCase
{

    use RefreshDatabase;

    /**
     * Test we can create {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_create_{{$resource}}()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.create');

      $this->actingAs($admin, $guard);

      $this->faker = \Faker\Factory::create();
      $firstData = 'raw_data';
      Livewire::test({{$datatableComponent}}::class)
      @foreach($columnFakerMappings as $index=>$col)
        @if($index == 1)
        ->set('state.{{$col['name']}}', $firstData)
        @else
        ->set('state.{{$col['name']}}', {!!$col['faker']!!})
        @endif
      @endforeach
        ->call('submit');

        $this->assertTrue({{ucfirst($modelBaseName)}}::where('{{$columnFakerMappings->first()['name']}}', $firstData)->exists());
       
    }

    /**
     * Test we can update {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_update_{{$resource}}()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.edit');

      $this->actingAs($admin, $guard);

      $model = {{$modelBaseName}}::factory()->create();

      $this->faker = \Faker\Factory::create();
      $firstData = 'raw_data';
      Livewire::test({{$datatableComponent}}::class)
      ->call('launch{{ucfirst($modelBaseName)}}EditModal', [$model->id])
      @foreach($columnFakerMappings as $index=>$col)
        @if($index == 1)
        ->set('state.{{$col['name']}}', $firstData)
        @else
        ->set('state.{{$col['name']}}', {!!$col['faker']!!})
        @endif
      @endforeach
        
        ->call('editSubmit');

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
        $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.create');
  
        $this->actingAs($admin, $guard);
        $this->faker = \Faker\Factory::create();
        Livewire::test({{$datatableComponent}}::class)
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('state.{{$col['name']}}', '')
          @else
          ->set('state.{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
          ->call('submit')
          ->assertHasErrors(['state.{{$columnFakerMappings->first()['name']}}'=> 'required']);
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
        Livewire::test({{$datatableComponent}}::class)
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('state.{{$col['name']}}', '')
          @else
          ->set('state.{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
          ->call('submit')
          ->assertForbidden();
    }




  





}
