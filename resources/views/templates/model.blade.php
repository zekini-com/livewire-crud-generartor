@php echo "<?php";
@endphp

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
@if($hasDeletedAt)
    use Illuminate\Database\Eloquent\SoftDeletes;
@endif

class {{$modelBaseName}} extends Model
{
    
    /**
     * Allowed fillable items
     *
     * @var array
     */
    protected $fillable = [ 
    @foreach($vissibleColumns as $col)
    "{{$col['name']}}",
    @endforeach
    ];
   
   @if($hasDeletedAt)
        use SoftDeletes;
    @endif
}
