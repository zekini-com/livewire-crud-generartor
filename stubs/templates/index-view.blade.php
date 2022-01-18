<div>

    <x-slot name="header">
        <span class="font-semibold text-xl text-gray-800 leading-tight">
            {{$resource}}
        </span>
    </x-slot>



    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

       
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

                            {{"@"}}include('livewire.{{strtolower($viewName)}}.partials.form')

                        </div>
                        

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Create
                            </button>
                            <button wire:click="$toggle('{{$modelVariableName}}CreateModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
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

                            {{"@"}}include('livewire.{{strtolower($viewName)}}.partials.form')

                        </div>

                        <!--footer-->
                        <div class="flex items-center justify-end p-6 border-t border-solid border-gray-200 rounded-b">
                            <button type="submit" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                Update
                            </button>
                            <button wire:click="$toggle('{{$modelVariableName}}EditModal')" class="text-black-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
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