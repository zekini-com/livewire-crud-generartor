@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Requests\Admin\{{$modelBaseName}}\Destroy{{ $modelBaseName }};
use App\Http\Requests\Admin\{{$modelBaseName}}\Index{{ $modelBaseName }};

class Edit{{ucfirst($modelBaseName)}} extends Component
{

    public ${{strtolower($modelBaseName)}};

    public function mount($id)
    {
        $this->{{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::find($id);
    }

    public function render()
    {
        return view('livewire.edit-{{strtolower($modelBaseName)}}', [
            '{{strtolower($modelBaseName)}}'=> $this->{{strtolower($modelBaseName)}}
        ]);
    }

    public function update()
    {

    }

   
}
