@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Requests\Admin\{{$modelBaseName}}\Destroy{{ $modelBaseName }};
use App\Http\Requests\Admin\{{$modelBaseName}}\Index{{ $modelBaseName }};
use App\Http\Requests\Admin\{{$modelBaseName}}\Store{{ $modelBaseName }};
use App\Http\Requests\Admin\{{$modelBaseName}}\Update{{ $modelBaseName }};
use {{ $modelFullName }};

class List{{ucfirst($modelBaseName)}} extends Component
{ 

    @if($canBeTrashed)
    public $canBeTrashed = true;
    @else
    public $canBeTrashed = false;
    @endif
    
    /**
     * Checks if a user is viewing trashed
     *
     * @var bool
     */
    public $isViewingTrashed = false;
    
    @foreach($vissibleColumns as $col)
    
    public ${{$col['name']}};

    @endforeach

    /**
     * Renders the component
     *
     * @return View
     */
    public function render()
    {
        $data = ($this->isViewingTrashed && $this->canBeTrashed) ? {{$modelBaseName}}::onlyTrashed()->get() : {{$modelBaseName}}::all();

        return view('livewire.list-{{strtolower($modelBaseName)}}', [
            'data'=> $data
        ])->extends('zekini/livewire-crud-generator::admin.layout.default')
        ->section('body');
    }
    
    /**
     * soft Deletes a {{$modelBaseName}}
     *
     * @return void
     */
    public function delete($id)
    {
        ${{strtolower($modelBaseName)}}  = {{ucfirst($modelBaseName)}}::withTrashed()->find($id);
        $this->isViewingTrashed ? ${{strtolower($modelBaseName)}}->forceDelete() : ${{strtolower($modelBaseName)}}->delete();
    }
    
    /**
     * Hards Delete a {{$modelBaseName}}
     *
     * @param  mixed $id
     * @return void
     */
    public function restore($id)
    {
        ${{strtolower($modelBaseName)}}  = {{ucfirst($modelBaseName)}}::withTrashed()->find($id);
        ${{strtolower($modelBaseName)}}->restore();
    }

    
    /**
     * Switch the content mode 
     *
     * @return void
     */
    public function toggleTrash()
    {
        $this->isViewingTrashed = ! $this->isViewingTrashed;
    }

   
}
