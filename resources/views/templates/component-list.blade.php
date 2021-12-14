@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Zekini\CrudGenerator\Traits\HandlesFile;
use Zekini\CrudGenerator\Helpers\CrudModelList;
use {{ $modelFullName }};
use Illuminate\Support\Str;

class List{{ucfirst($modelBaseName)}} extends Component
{ 

    use HandlesFile, AuthorizesRequests;

    @if($canBeTrashed)
    protected $canBeTrashed = true;
    @else
    protected $canBeTrashed = false;
    @endif
    
    /**
     * Checks if a user is viewing trashed
     *
     * @var bool
     */
    public $isViewingTrashed = false;

    protected $model = {{$modelBaseName}}::class;
    
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
        $this->authorize('admin.{{ strtolower($modelBaseName) }}.index');

        $data = CrudModelList::getList({{$modelBaseName}}::class, $this->isViewingTrashed, $this->canBeTrashed);

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
        $this->authorize('admin.{{ strtolower($modelBaseName) }}.delete');
     
        ${{strtolower($modelBaseName)}}  = $this->isViewingTrashed ? {{ucfirst($modelBaseName)}}::withTrashed()->find($id) : {{ucfirst($modelBaseName)}}::find($id) ;
        $this->isViewingTrashed ? ${{strtolower($modelBaseName)}}->forceDelete() : ${{strtolower($modelBaseName)}}->delete();

        @if($hasFile)
        if ($this->isViewingTrashed){
            // delete the old image
            $this->deleteFile(${{strtolower($modelBaseName)}}->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});
        }
        @endif
   
    }
    
    /**
     * Hards Delete a {{$modelBaseName}}
     *
     * @param  mixed $id
     * @return void
     */
    public function restore($id)
    {
        $this->authorize('admin.{{ strtolower($modelBaseName) }}.delete');

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
