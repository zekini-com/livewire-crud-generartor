@php echo "<?php";
@endphp

namespace Tests\Unit\{{$modelBaseName}};
@php $datatableComponent = ucfirst(Str::plural($modelBaseName)); @endphp
use Tests\TestCase;
use App\Models\{{$modelBaseName}};
use App\Http\Livewire\Create{{$modelBaseName}};
use App\Http\Livewire\{{ucfirst(Str::plural($modelBaseName))}}\{{$datatableComponent}};
use Livewire\Livewire;
@php $isActivityLogModel = ucfirst($modelBaseName) == 'ActivityLog'; @endphp
class {{$modelBaseName}}Test extends TestCase
{
  /**
   * @group {{$resource}}_test
   */
  public function testWeCanCreate{{$resource}}(): void
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.create');

      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';
      Livewire::test({{$datatableComponent}}::class)
      @foreach($columnFakerMappings as $index=>$col)
        @if($index == 1)
        ->set('state.{{$col['name']}}', $firstData)
        @else
          @if($isActivityLogModel && in_array($col['name'], ['subject_id', 'causer_id']))
          ->set('state.{{$col['name']}}', {!!'$this->faker->randomDigit()'!!})
          @continue
          @endif
        ->set('state.{{$col['name']}}', {!!$col['faker']!!})
        @endif
      @endforeach
      @foreach($pivots as $pivot)
          @php
            $pivotModelFactory = '\App\Models\\'.ucfirst(Str::singular($pivot['table']));
          @endphp
          ->set('state.{{$pivot["table"]}}', {{ $pivotModelFactory }}::factory()->create()->id)
       @endforeach
        ->call('submit')
        ->assertHasNoErrors();

        $this->assertTrue({{ucfirst($modelBaseName)}}::where('{{$columnFakerMappings->first()['name']}}', $firstData)->exists());
    }

    /**
     * @group {{$resource}}_test
     */
    public function testWeCanUpdate{{$resource}}(): void
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = {{$adminModel}}::factory()->create();
      $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.edit');

      $this->actingAs($admin, $guard);

      $model = {{$modelBaseName}}::factory()->create();

      $firstData = 'raw_data';
      Livewire::test({{$datatableComponent}}::class)
      ->call('launch{{ucfirst($modelBaseName)}}EditModal', [$model->id])
      @foreach($columnFakerMappings as $index=>$col)
        @if($index == 1)
        ->set('state.{{$col['name']}}', $firstData)
        @else
        @if($isActivityLogModel && in_array($col['name'], ['subject_id', 'causer_id']))
          ->set('state.{{$col['name']}}', {!!'$this->faker->randomDigit()'!!})
          @continue
          @endif
        ->set('state.{{$col['name']}}', {!!$col['faker']!!})
        @endif
      @endforeach
      @foreach($pivots as $pivot)
          @php
            $pivotModelFactory = '\App\Models\\'.ucfirst(Str::singular($pivot['table']));
          @endphp
          ->set('state.{{$pivot["table"]}}', {{ $pivotModelFactory }}::factory()->create()->id)
       @endforeach
        ->call('editSubmit')
        ->assertHasNoErrors();

        $this->assertTrue({{ucfirst($modelBaseName)}}::where('{{$columnFakerMappings->first()['name']}}', $firstData)->exists());
    }

    /**
     * @group {{$resource}}_test
     */
    public function test{{$columnFakerMappings->first()['name']}}IsRequired(): void
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = {{$adminModel}}::factory()->create();
        $admin->givePermissionTo('admin.{{ strtolower($modelDotNotation)}}.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test({{$datatableComponent}}::class)
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('state.{{$col['name']}}', '')
          @else
          @if($isActivityLogModel && in_array($col['name'], ['subject_id', 'causer_id']))
          ->set('state.{{$col['name']}}', {!!'$this->faker->randomDigit()'!!})
          @continue
          @endif
          ->set('state.{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
        @foreach($pivots as $pivot)
          @php
            $pivotModelFactory = '\App\Models\\'.ucfirst(Str::singular($pivot['table']));
          @endphp
          ->set('state.{{$pivot["table"]}}', {{ $pivotModelFactory }}::factory()->create()->id)
       @endforeach
          ->call('submit')
          ->assertHasErrors(['state.{{$columnFakerMappings->first()['name']}}'=> 'required']);
    }

    /**
     * @group {{$resource}}_test
     */
    public function testAccessIsForbidden(): void
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = {{$adminModel}}::factory()->create();
  
        $this->actingAs($admin, $guard);

        Livewire::test({{$datatableComponent}}::class)
        @foreach($columnFakerMappings as $index=>$col)
          @if($index == 1)
          ->set('state.{{$col['name']}}', '')
          @else
          @if($isActivityLogModel && in_array($col['name'], ['subject_id', 'causer_id']))
          ->set('state.{{$col['name']}}', {!!'$this->faker->randomDigit()'!!})
          @continue
          @endif
          ->set('state.{{$col['name']}}', {!!$col['faker']!!})
          @endif
        @endforeach
        @foreach($pivots as $pivot)
          @php
            $pivotModelFactory = '\App\Models\\'.ucfirst(Str::singular($pivot['table']));
          @endphp
          ->set('state.{{$pivot["table"]}}', {{ $pivotModelFactory }}::factory()->create()->id)
       @endforeach
          ->call('submit')
          ->assertForbidden();
    }
}
