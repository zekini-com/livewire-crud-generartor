@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Requests\Admin\{{$modelBaseName}}\Update{{ $modelBaseName }};
use {{ $modelFullName }};
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
    HandlesFile;

    public $success;

    public ${{strtolower($modelBaseName)}};

    @foreach($vissibleColumns as $col)
    
    /**
     * @var string {{$col['name']}}
     * 
     */
    public ${{$col['name']}};
    @endforeach


    public function mount(${{strtolower($modelBaseName)}})
    {
    $this->{{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::find(${{strtolower($modelBaseName)}});
    @foreach($vissibleColumns as $col)
    $this->{{$col['name']}} = $this->{{strtolower($modelBaseName)}}->{{$col['name']}};
    @endforeach

    }

    public function render()
    {
        return view('livewire.edit-{{strtolower($modelBaseName)}}')
        ->extends('zekini/livewire-crud-generator::admin.layout.default')
        ->section('body');
    }

    public function update(Update{{ucfirst($modelBaseName)}} ${{Str::camel('update'.$modelBaseName)}})
    {
        //access control
        ${{Str::camel('update'.$modelBaseName)}}->authorize();

        // validate request
        $this->validate(${{Str::camel('update'.$modelBaseName)}}->getRuleSet());

        //image processing
        @if($hasFile)
        if ($this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}} instanceof TemporaryUploadedFile){
            $this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}} = $this->getFile($this->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});

            // delete the old image
            $this->deleteFile($this->{{strtolower($modelBaseName)}}->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});
        }
           
        @endif

        ${{strtolower($modelBaseName)}} = $this->{{strtolower($modelBaseName)}}->update([
        @foreach($vissibleColumns as $col)
            '{{$col['name']}}'=> $this->{{$col['name']}},
        @endforeach
        ]);
        if(isset(${{strtolower($modelBaseName)}})){
            $this->success = true;
        }
    }

   
}
