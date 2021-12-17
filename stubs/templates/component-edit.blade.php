@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use {{ $modelFullName }};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Zekini\CrudGenerator\Traits\HandlesFile;
@if($hasFile)
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
@endif
class Edit{{ucfirst($modelBaseName)}} extends Component
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
     * @var {{ucfirst($modelBaseName)}} {{$col['name']}}
     * 
     */
    public ${{$col['name']}};
    @endforeach


    public function mount(${{strtolower($modelBaseName)}})
    {
    $this->{{strtolower($modelBaseName)}}Model = {{ucfirst($modelBaseName)}}::find(${{strtolower($modelBaseName)}});
    @foreach($vissibleColumns as $col)
    $this->{{$col['name']}} = $this->{{strtolower($modelBaseName)}}Model->{{$col['name']}};
    @endforeach

    }

    public function render()
    {
        return view('livewire.edit-{{strtolower($modelBaseName)}}')
        ->extends('zekini/livewire-crud-generator::admin.layout.default')
        ->section('body');
    }

    public function update()
    {
        //access control
        $this->authorize('admin.{{ strtolower($modelBaseName) }}.edit');

        // validate request
        $this->validate();

        //image processing
        @if($hasFile)
        if (@$this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}}[0] instanceof TemporaryUploadedFile){
            $this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}} = $this->getFile($this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});

            // delete the old image
            $this->deleteFile($this->{{strtolower($modelBaseName)}}Model->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});
        }
           
        @endif

        ${{strtolower($modelBaseName)}} = $this->{{strtolower($modelBaseName)}}Model->update([
        @foreach($vissibleColumns as $col)
            '{{$col['name']}}'=> $this->{{$col['name']}},
        @endforeach
        ]);
        if(isset(${{strtolower($modelBaseName)}})){
            $this->success = true;
        }
    }

   
}
