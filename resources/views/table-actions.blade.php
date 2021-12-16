<div class="flex space-x-1 justify-around">
    @php
        $url = url("admin/$model/$id/edit");
        $viewName = "edit-$model";
    @endphp

    <x-c.modal>
        <x-slot name="btn">
            <a href="#"  class="p-1 text-teal-600 hover:bg-teal-600 hover:text-white rounded">
                Edit
            </a>
        </x-slot>

        <x-slot name="body">
            @livewire($viewName, [$model => $id])
        </x-slot>
    </x-c.modal>

    @if($canBeTrashed)
    <x-c.modal>
        <x-slot name="btn">
            <a href="#"  class="p-1 text-teal-600 hover:bg-teal-600 hover:text-white rounded">
                Hard Delete
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Hard Delete') }} 
        </x-slot>

        <x-slot name="body">
         
            <div class="mt-10 text-gray-700">
                {{ __('Are you sure?')}}
            </div>
            <div class="mt-10 flex justify-center">
                <span class="mr-2">
                    <button x-on:click="open = false" x-bind:disabled="working" class="w-32 shadow-sm inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:shadow-outline-teal active:bg-gray-700 transition ease-in-out duration-150">
                        {{ __('No')}}
                    </button>
                </span>
                <span x-on:click="working = !working">
                    <button wire:click="forceDelete({{ $id }})" class="w-32 shadow-sm inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-teal active:bg-red-700 transition ease-in-out duration-150">
                        {{ __('Yes')}}
                    </button>
                </span>
            </div>
                    
        </x-slot>
    </x-c.modal>
    @endif
    

    @include('datatables::delete', ['value' => $id])
</div>