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
        $data = {{$modelBaseName}}::all();

        return view('livewire.list-{{strtolower($modelBaseName)}}', [
            'data'=> $data
        ])->layout('zekini/livewire-crud-generator::admin.layout.default');
    }
    
    /**
     * soft Deletes a {{$modelBaseName}}
     *
     * @return void
     */
    public function softDelete()
    {

    }

    public function hardDelete()
    {
        
    }

   
}
