@foreach($vissibleColumns as $col)
    @if($col['type'] == 'boolean')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $col['name'] }}</label>
            <div class="col-xl-8">
                <input class="form-check-input" id="{{ $col['name'] }}" type="checkbox"   name="{{ $col['name'] }}">
            </div>
        </div>
    @elseif($col['type'] == 'text')
        <div class="form-group row align-items-center" >
            <label for="{{ $col['name'] }}" class="col-form-label text-md-right">{{ $col['name'] }}</label>
            <div class="col-md-9 col-xl-8">
                <div>
                    <textarea class="form-control"  id="{{ $col['name'] }}" name="{{ $col['name'] }}"></textarea>
                </div>
            </div>
        </div>
    @elseif($col['type'] == 'datetime')
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $col['name'] }}</label>
            <div class="col-xl-8">
                <input id="{{ $col['name'] }}" type="datetime"   name="{{ $col['name'] }}">
            </div>
        </div>
    @else
        <div class="form-group row align-items-center" >
            <label for="{{$col['name']}}" class="col-form-label text-md-right" >{{ $col['name'] }}</label>
            <div class="col-xl-8">
                <input class="form-check-input" id="{{ $col['name'] }}" type="text"   name="{{ $col['name'] }}">
            </div>
        </div>
    @endif
@endforeach

