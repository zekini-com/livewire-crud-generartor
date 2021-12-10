@php echo "<?php";
@endphp

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Requests\Admin\{{$modelBaseName}}\Destroy{{ $modelBaseName }};
use App\Http\Requests\Admin\{{$modelBaseName}}\Index{{ $modelBaseName }};
use {{ $modelFullName }};

class Edit{{ucfirst($modelBaseName)}} extends Component
{

    public ${{strtolower($modelBaseName)}};

    public function mount(${{strtolower($modelBaseName)}})
    {
        $this->{{strtolower($modelBaseName)}} = {{ucfirst($modelBaseName)}}::find(${{strtolower($modelBaseName)}});
    }

    public function render()
    {
        return view('livewire.edit-{{strtolower($modelBaseName)}}', [
            '{{strtolower($modelBaseName)}}'=> $this->{{strtolower($modelBaseName)}}
        ])->layout('zekini/livewire-crud-generator::admin.layout.default');
    }

    public function update()
    {

    }

   
}
