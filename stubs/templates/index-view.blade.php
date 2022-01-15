<div>

    <x-slot name="header">
        <span class="font-semibold text-xl text-gray-800 leading-tight">
            {{$resource}}
        </span>
    </x-slot>



    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <button wire:click="$emit('launch{{ucfirst($modelVariableName)}}CreateModal')" type="button" class="flex items-center space-x-2 py-2 px-3 border border-green-400 rounded-md bg-white text-green-500 text-xs leading-4 font-medium uppercase tracking-wider hover:bg-green-200 focus:outline-none"><span>Create</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"></path>
        </svg></button>

        <div>
               
               <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                   <input type="checkbox" wire:click="$emit('toggleSoftDeletes')" name="toggle" id="toggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                   <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
               </div>
               <label for="toggle" class="text-xs text-gray-700">Soft Deletes</label>
           </div>
            @php
            $tag = "<livewire:$componentName />";
           
            $endJetstreamDialog = "</x-jet-dialog-modal>";
            $titleSlot = "<x-slot name='title'>";
            $endSlot =  "</x-slot>";
            $contentSlot  = "<x-slot name='content'>";
            $footerSlot = "<x-slot name='footer'>";
            $startJetstreamDialog = "<x-jet-dialog-modal";

            @endphp
            {!! $tag !!}

        </div>



        {!! $startJetstreamDialog !!} wire:model="{{$wireCreate}}">
            {!! $titleSlot !!}

                <div class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">

                    <h1 class="text-grey-200 font-semibold">
                        Create {{$resource}}
                    </h1>
                    {{ $buttons ?? '' }}
                </div>

            {!!$endSlot !!}

            {!! $contentSlot !!}

                <div class="flex col-span-12 gap-4 mt-4">

                    <form wire:submit.prevent="submit" class="w-full">
                        <!--body-->
                        <div class="relative p-6 flex-auto">

                            {{"@"}}include('livewire.{{strtolower($resource)}}.partials.form')

                        </div>
                        

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Create
                            </button>
                            <button wire:click="$toggle('{{strtolower($modelVariableName)}}CreateModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Close
                            </button>

                        </div>
                    </form>

                </div>

            {!! $endSlot !!}

            {!! $footerSlot !!}

            {!!$endSlot!!}

        {!!  $endJetstreamDialog !!}


        <!-- Edit section -->
        {!! $startJetstreamDialog !!} wire:model="{{$wireEdit}}">

            {!! $titleSlot !!}

                <div class="flex items-start justify-between p-5 border-b border-solid border-gray-200 rounded-t">

                    <h1 class="text-grey-200 font-semibold">
                        Edit {{$modelVariableName}}
                    </h1>
                    {{ $buttons ?? '' }}
                </div>

            {!!$endSlot !!}

            {!! $contentSlot !!}

                <div class="flex col-span-12 gap-4 mt-4">

                    <form wire:submit.prevent="editSubmit" class="w-full">
                        <!--body-->
                        <div class="relative p-6 flex-auto">

                            {{"@"}}include('livewire.{{strtolower($resource)}}.partials.form')

                        </div>

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Update
                            </button>
                            <button wire:click="$toggle('{{strtolower($modelVariableName)}}EditModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Close
                            </button>

                        </div>
                    </form>

                </div>

            {!! $endSlot !!}

            {!! $footerSlot !!}

            {!!$endSlot!!}

        {!!  $endJetstreamDialog !!}

      



    </div>
</div>