@php echo "<?php";
@endphp

namespace Tests\Unit\{{$modelBaseName}};

use Tests\TestCase;
use App\Models\{{$modelBaseName}};
use App\Http\Livewire\Create{{$modelBaseName}};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class {{$modelBaseName}}StoreTest extends TestCase
{

    use RefreshDatabase;

    /** @test  */
    function test_{{strtolower($modelBaseName)}}_creation_page_contains_livewire_component()
    {
        $guard = config('zekini-admin.defaults.guard');
      $this->actingAs({{$adminModel}}::factory()->create(), $guard);

        $this->get('/admin/{{strtolower($modelBaseName)}}/create')->assertSeeLivewire('{{$viewName}}');
    }

    /**
     * Test we can create {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_create_{{$resource}}()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($resource)}}.create');

      $this->actingAs($admin, $guard);

      $this->faker = \Faker\Factory::create();
      $firstData = 'raw_data';
      Livewire::test(Create{{$modelBaseName}}::class)
      @foreach($columnFakerMappings as $index=>$col)
        @if($index == 1)
        ->set('{{$col['name']}}', $firstData)
        @else
        ->set('{{$col['name']}}', {!!$col['faker']!!})
        @endif
      @endforeach
        ->call('create');

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
        $admin->givePermissionTo('admin.{{ strtolower($resource)}}.create');
  
        $this->actingAs($admin, $guard);
        $this->faker = \Faker\Factory::create();
        Livewire::test(Create{{$modelBaseName}}::class)
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('{{$col['name']}}', '')
          @else
          ->set('{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
          ->call('create')
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
        Livewire::test(Create{{$modelBaseName}}::class)
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('{{$col['name']}}', '')
          @else
          ->set('{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
          ->call('create')
          ->assertForbidden();
    }





}
