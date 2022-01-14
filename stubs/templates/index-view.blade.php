<div>

    <x-slot name="header">
        <span class="font-semibold text-xl text-gray-800 leading-tight">
            {{$resource}}
        </span>
    </x-slot>



    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <button wire:click="launch{{ucfirst($modelVariableName)}}CreateModal" class="flex items-center space-x-2 px-3 border border-green-400 rounded-md bg-white text-green-500 text-xs leading-4 font-medium uppercase tracking-wider hover:bg-green-200 focus:outline-none"><span>Export</span>
                        <svg class="h-5 w-5 stroke-current m-2" fill="none" viewBox="0 0 384 512"><path fill="currentColor" d="M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zM332.1 128H256V51.9l76.1 76.1zM48 464V48h160v104c0 13.3 10.7 24 24 24h104v288H48zm212-240h-28.8c-4.4 0-8.4 2.4-10.5 6.3-18 33.1-22.2 42.4-28.6 57.7-13.9-29.1-6.9-17.3-28.6-57.7-2.1-3.9-6.2-6.3-10.6-6.3H124c-9.3 0-15 10-10.4 18l46.3 78-46.3 78c-4.7 8 1.1 18 10.4 18h28.9c4.4 0 8.4-2.4 10.5-6.3 21.7-40 23-45 28.6-57.7 14.9 30.2 5.9 15.9 28.6 57.7 2.1 3.9 6.2 6.3 10.6 6.3H260c9.3 0 15-10 10.4-18L224 320c.7-1.1 30.3-50.5 46.3-78 4.7-8-1.1-18-10.3-18z"></path></svg></button>
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