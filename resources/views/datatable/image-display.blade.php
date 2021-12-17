<div>
    @foreach(json_decode($file) as $file)
        <img src="{{asset('storage/'.$file)}}" width="100" height="100">
    @endforeach
</div>