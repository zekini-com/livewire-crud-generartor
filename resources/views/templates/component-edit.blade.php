@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Requests\Admin\{{$modelBaseName}}\Update{{ $modelBaseName }};
use {{ $modelFullName }};

class Edit{{ucfirst($modelBaseName)}} extends Component
{

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
