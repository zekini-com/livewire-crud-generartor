@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Requests\Admin\{{$modelBaseName}}\Store{{ $modelBaseName }};
use {{ $modelFullName }};

class Create{{ucfirst($modelBaseName)}} extends Component
{ 

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
    public function create(Store{{ucfirst($modelBaseName)}} ${{Str::camel('store'.$modelBaseName)}})
    {
        //access control
        ${{Str::camel('store'.$modelBaseName)}}->authorize();

        // validate request
        $this->validate(${{Str::camel('store'.$modelBaseName)}}->getRuleSet());

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
