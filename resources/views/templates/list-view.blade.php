{{'@'}}extends('zekini/livewire-crud-generator::admin.layout.default')
{{'@'}}section('body')
  
    <x-c.modal>
        <x-slot name="btn">
            <a href="#"  class="w-32 shadow-sm inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-teal active:bg-red-700 transition ease-in-out duration-150">
                Create {{$resource}}
            </a>
        </x-slot>

        <x-slot name="body">
            {{'@'}}livewire('{{$createView}}')
        </x-slot>
    </x-c.modal>

    @php
        $tag = "<livewire:$componentName/>";
    @endphp
    {!! $tag !!}
{{'@'}}endsection