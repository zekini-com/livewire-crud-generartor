@php echo "<?php";
@endphp

namespace App\Http\Livewire\{{Str::plural(ucfirst($modelBaseName))}};

use Livewire\Component;
use {{ $modelFullName }};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Zekini\CrudGenerator\Traits\HandlesFile;

use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
use Zekini\Generics\Helpers\FlashHelper;


@php($lowerModelBaseName = Str::camel($modelBaseName))

class {{Str::plural($modelBaseName)}} extends Component
{
    @if(! $isReadonly)
    use WithFileUploads;
    use HandlesFile;
    use AuthorizesRequests;

    public {{$modelBaseName}} ${{$lowerModelBaseName}};

    public $state;


    public ${{$lowerModelBaseName}}CreateModal = false;

    public ${{$lowerModelBaseName}}EditModal = false;

    protected $listeners = [
        'launch{{$modelBaseName}}CreateModal',
        'launch{{$modelBaseName}}EditModal',
        'flashMessageEvent'=> 'flashMessageEvent'
    ];

    public function mount()
    {
       $this->state = [];
    }
    @endif

    public function render()
    {
        return view('livewire.{{$viewName}}.index')
        ->extends('zekini/livewire-crud-generator::admin.layout.default')
        ->section('body');
    }

    
    @if(! $isReadonly)
    public function submit()
    {
        //access control
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.create');

        $this->validate();

        $this->create($this->state);

        $this->flashMessageEvent('Item successfully created');

        $this->emit('refreshLivewireDatatable');

        $this->resetState();

        $this->closeModalButton();
    }

    public function editSubmit()
    {
        //access control
        $this->authorize('admin.{{strtolower($modelDotNotation)}}.edit');

        $this->validate();

        $this->update($this->state, $this->state['id']);

        $this->flashMessageEvent('Item successfully updated');

        $this->emit('refreshLivewireDatatable');

        $this->resetState();

        $this->closeModalButton();
    }

    public function flashMessageEvent($message)
    {
        FlashHelper::success($message, $this);
    }

    public function closeModalButton()
    {
        $this->{{$lowerModelBaseName}}CreateModal = false;
        $this->{{$lowerModelBaseName}}EditModal = false;
    }

    public function launch{{$modelBaseName}}CreateModal()
    {
        $this->{{$lowerModelBaseName}}CreateModal = true;
    }

    public function launch{{$modelBaseName}}EditModal({{$modelBaseName}} ${{$lowerModelBaseName}})
    {

        $this->state = ${{$lowerModelBaseName}}->toArray();
        @foreach($pivots as $pivot)
         $this->state['{{$pivot["table"]}}'] = ${{$lowerModelBaseName}}->{{$pivot["table"]}}()->allRelatedIds()->toArray();
        @endforeach
        $this->{{$lowerModelBaseName}}EditModal = true;
    }

   
    protected function rules()
    {
        return [
            @foreach($vissibleColumns as $col)
            @if($userModel && in_array($col['name'], ['email', 'name']))
            'state.{{$col['name']}}'=> 'required|unique:{{$tableName}},{{$col["name"]}},'.@$this->state['id'],
            @else
            'state.{{$col['name']}}'=> 'required',
            @endif
            
            @endforeach
        ];
    }

    protected function resetState()
    {
        $this->state = [];
    }

    private function create($data)
    {
        // image processing
        @if($hasFile)
            $data['{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}}'] = $this->getFile($data['{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}}']);
        @endif

        $model = {{$modelBaseName}}::create($data);
        @foreach($pivots as $pivot)
        @if($modelBaseName == 'ZekiniAdmin')
        $model->{{$pivot['table']}}()->syncWithPivotValues($this->state['{{$pivot['table']}}'], [
            'model_type'=> 'Zekini\CrudGenerator\Models\ZekiniAdmin'
        ]);
        @else
        $model->{{$pivot['table']}}()->sync($this->state['{{$pivot['table']}}']);
        @endif
        @endforeach
    }


    private function update($data, $id)
    {
        $model = {{$modelBaseName}}::findOrFail($id);

        //image processing
        @if($hasFile)
        if (@$data['{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}}'][0] instanceof TemporaryUploadedFile){
            $data['{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}}'] = $this->getFile($data['{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}}']);

            // delete the old image
            $this->deleteFile($model->{{ $vissibleColumns->first(function($item){  return $item['name'] == 'image'; }) ? 'image' : 'file'}});
        }
           
        @endif
      
        $model->update($data);
        @foreach($pivots as $pivot)
        @if($modelBaseName == 'ZekiniAdmin')
        $model->{{$pivot['table']}}()->syncWithPivotValues($this->state['{{$pivot['table']}}'], [
            'model_type'=> 'Zekini\CrudGenerator\Models\ZekiniAdmin'
        ]);
        @else
        $model->{{$pivot['table']}}()->sync($this->state['{{$pivot['table']}}']);
        @endif
        
        @endforeach
    }
    @endif
   
}
