@php
$openBlade = '{{';
$closeBlade = '}}';
@endphp
@foreach($vissibleColumns as $col)
    @php
        $textLabel = str_replace('_',' ',ucfirst($col['name']));
        $wireModel = $col['name'];
    @endphp
    @if(in_array($col['name'], $belongsTo))
    <div class="form-group row align-items-center" >

        <label for="{{$col['name']}}" class="col-form-label text-md-right col-md-3" >{{ $textLabel }}</label>
        <div class="col-xl-8">
            <select class="form-control col-md-9" wire:model="{{$wireModel}}" id="{{ $col['name'] }}"  name="{{ $col['name'] }}">
                {{'@'}}foreach(\App\Models\{{ucfirst(str_replace('_id','',$col['name']))}}::all() as $item)
                    <option  wire:key="{!! $openBlade !!} $item->id {!! $closeBlade !!}" value="{!! $openBlade !!} $item->id {!! $closeBlade !!}"> {!! $openBlade !!}  $item->{{$recordTitleMap[Str::plural(str_replace('_id', '', $col['name']))]}} {!! $closeBlade !!}</option>
                {{'@'}}endforeach
            </select>
            {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
        </div>
    </div>
    @elseif($col['type'] == 'boolean')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right col-md-3" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input class="form-check-input col-md-9" wire:model="{{$wireModel}}" id="{{ $col['name'] }}" type="checkbox"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
    @elseif($col['type'] == 'text')
        <div class="form-group row align-items-center" >
            <label for="{{ $col['name'] }}" class="col-form-label text-md-right col-md-3">{{ $textLabel }}</label>
            <div class="col-md-9 col-xl-8">
                <div>
                    <textarea class="form-control col-md-9"  wire:model="{{$wireModel}}"  id="{{ $col['name'] }}" name="{{ $col['name'] }}"></textarea>
                    {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
                </div>
            </div>
        </div>
    @elseif($col['type'] == 'datetime')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right col-md-3" >{{ $textLabel }}</label>
            <div class="col-xl-8 col-md-9">
                <input id="{{ $col['name'] }}" type="datetime" wire:model="{{$wireModel}}"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
    @else

        @if($col['name'] == 'image' || $col['name'] == 'file')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right col-md-3" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input class="form-control col-md-9" id="{{ $col['name'] }}" wire:model="{{$wireModel}}" type="file"   name="{{ $col['name'] }}" multiple>
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
        @else
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right col-md-3" >{{ $textLabel }}</label>
            <div class="col-xl-8">
                <input class="form-control col-md-9" id="{{ $col['name'] }}" wire:model="{{$wireModel}}" type="text"   name="{{ $col['name'] }}">
                {{'@'}}error('{{$col['name']}}') <span> {!! $openBlade !!} $message {!! $closeBlade !!} </span> {{'@'}}enderror
            </div>
        </div>
        @endif
    @endif
@endforeach

