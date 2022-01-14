@php echo "<?php";
@endphp

namespace App\Http\Livewire\{{Str::plural(ucfirst($modelBaseName))}};

use Livewire\Component;
use {{ $modelFullName }};
use Zekini\CrudGenerator\Traits\HandlesFile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
@if($hasFile)
use Livewire\WithFileUploads;
@endif

class Create extends Component
{ 
    use
    @if($hasFile)
    WithFileUploads,
    @endif
    AuthorizesRequests, HandlesFile;

    protected $rules = [
    @foreach($vissibleColumns as $col)
    '{{$col['name']}}'=> 'required',
    @endforeach
    ];

    public $success;

    @foreach($vissibleColumns as $col)
    
    /**
     * @var string {{$col['name']}}
     * 
     */
    public ${{$col['name']}};

    @endforeach

    @foreach($pivots as $pivot)
    public ${{$pivot['table']}};
    @endforeach

    /**
     * Renders the component
     *
     * @return View
     */
    public function render()
    {
        $data = {{$modelBaseName}}::all();

        return view('livewire.create-{{Str::kebab($modelBaseName)}}', [
            'data'=> $data
        ])->extends('zekini/livewire-crud-generator::admin.layout.default')
        ->section('body');
    }

    
    /**
     * Creates a {{$modelBaseName}}
     *
     * @return void
     */
    public function create()
    {
        //access control
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.create');

        // validate request
        $this->validate();

        // image processing
        @if($hasFile)
            $this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}} = $this->getFile($this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});
        @endif

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::forceCreate([
        @foreach($vissibleColumns as $col)
            '{{$col['name']}}'=> $this->{{$col['name']}},
        @endforeach

        @if($modelBaseName == 'ZekiniAdmin')
        //for admins add password
        'password'=> Hash::make('fakepassword')
        @endif

        ]);

        @foreach($pivots as $pivot)
        @if($modelBaseName == 'ZekiniAdmin')
        ${{strtolower($modelBaseName)}}->{{Str::singular($pivot['table'])}}()->syncWithPivotValues($this->{{$pivot['table']}}, [
            'model_type'=> 'Zekini\CrudGenerator\Models\ZekiniAdmin'
        ]);
        @else
        ${{strtolower($modelBaseName)}}->{{Str::singular($pivot['table'])}}()->sync($this->{{$pivot['table']}});
        @endif
        @endforeach

    if(isset(${{strtolower($modelBaseName)}})){
        $this->success = true;
    }

    }


   
}
