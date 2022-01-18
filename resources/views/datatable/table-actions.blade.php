<div class="flex space-x-1 justify-around">

@if($softdeletes)
    @include('zekini/livewire-crud-generator::datatable.hard-delete', ['value' => $id])

    @include('zekini/livewire-crud-generator::datatable.restore', ['value' => $id])
@else
    @include('zekini/livewire-crud-generator::datatable.edit', ['value' => $id, 'model'=> $model])

    @include('zekini/livewire-crud-generator::datatable.delete', ['value' => $id])

@endif
</div>