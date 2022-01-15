@php echo "<?php";
@endphp

namespace App\Http\Livewire\{{Str::plural(ucfirst($modelBaseName))}};

use Livewire\Component;
use {{ $modelFullName }};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Zekini\CrudGenerator\Traits\HandlesFile;
@if($hasFile)
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
@endif

@php($lowerModelBaseName = Str::camel($modelBaseName))

class {{Str::plural($modelBaseName)}} extends Component
{

    public {{$modelBaseName}} ${{$lowerModelBaseName}};

    public $state;


    public ${{$lowerModelBaseName}}CreateModal = false;

    public ${{$lowerModelBaseName}}EditModal = false;

    protected $listeners = [
        'launch{{$modelBaseName}}CreateModal',
        'launch{{$modelBaseName}}EditModal'
    ];

    public function mount()
    {
       $this->state = [];
    }

    public function render()
    {
        return view('livewire.{{$viewName}}.index')
        ->extends('zekini/livewire-crud-generator::admin.layout.default')
        ->section('body');
    }

    

    public function submit()
    {
        $this->validate();

        $this->create($this->state);

        $this->emit('showAlert', 'Created');

        $this->emit('refreshLivewireDatatable');

        $this->resetState();

        $this->closeModalButton();
    }

    public function editSubmit()
    {
        $this->validate();

        $this->update($this->state->toArray(), $this->state->id);

        $this->emit('showAlert', 'Updated');

        $this->emit('refreshLivewireDatatable');

        $this->resetState();

        $this->closeModalButton();
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
        $this->state = ${{$lowerModelBaseName}};
        $this->{{$lowerModelBaseName}}EditModal = true;
    }

   
    protected function rules()
    {
        return [
            @foreach($vissibleColumns as $col)
            'state.{{$col['name']}}'=> 'required',
            @endforeach
        ];
    }

    protected function resetState()
    {
        $this->state = [];
    }

    private function create($data)
    {
        return {{$modelBaseName}}::create($data);
    }


    private function update($data, $id)
    {
        return {{$modelBaseName}}::findOrFail($id)->update($data);
    }
   
}
