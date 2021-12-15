{{'@'}}extends('zekini/livewire-crud-generator::admin.layout.default')
{{'@'}}section('body')
    @php
        $tag = "<livewire:datatable model='App\Models\\$modelName' with='$relationships' include='$columns' searchable='$columns'  exportable/>";
    @endphp
    {!! $tag !!}
{{'@'}}endsection