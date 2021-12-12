@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
@foreach($vissibleColumns as $col)
    @php
        $textLabel = str_replace('_',' ',ucfirst($col['name']));
        $wireModel = $col['name'];
    @endphp
    @if($col['type'] == 'boolean')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input class="form-check-input" wire:model="{{$wireModel}}" id="{{ $col['name'] }}" type="checkbox"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
    @elseif($col['type'] == 'text')
        <div class="form-group row align-items-center" >
            <label for="{{ $col['name'] }}" class="col-form-label text-md-right">{{ $textLabel }}</label>
            <div class="col-md-9 col-xl-8">
                <div>
                    <textarea class="form-control"  wire:model="{{$wireModel}}"  id="{{ $col['name'] }}" name="{{ $col['name'] }}"></textarea>
                    {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
                </div>
            </div>
        </div>
    @elseif($col['type'] == 'datetime')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input id="{{ $col['name'] }}" type="datetime" wire:model="{{$wireModel}}"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
    @else

        @if($col['name'] == 'image' || $col['name'] == 'file')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input class="form-control" id="{{ $col['name'] }}" wire:model="{{$wireModel}}" type="file"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
        @else
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input class="form-control" id="{{ $col['name'] }}" wire:model="{{$wireModel}}" type="text"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
        @endif
    @endif
@endforeach

