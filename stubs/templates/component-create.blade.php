@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use {{ $modelFullName }};
use Zekini\CrudGenerator\Traits\HandlesFile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
@if($hasFile)
use Livewire\WithFileUploads;
@endif

class Create{{ucfirst($modelBaseName)}} extends Component
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

    /**
     * Renders the component
     *
     * @return View
     */
    public function render()
    {
        $data = {{$modelBaseName}}::all();

        return view('livewire.create-{{strtolower($modelBaseName)}}', [
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
        $this->authorize('admin.{{ strtolower($modelBaseName) }}.create');

        // validate request
        $this->validate();

        // image processing
        @if($hasFile)
            $this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}} = $this->getFile($this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});
        @endif

        ${{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::create([
        @foreach($vissibleColumns as $col)
            '{{$col['name']}}'=> $this->{{$col['name']}},
        @endforeach
    ]);

    if(isset(${{strtolower($modelBaseName)}})){
        $this->success = true;
    }

    }


   
}
