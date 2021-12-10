@php echo "<?php";
@endphp

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
@if($hasDeletedAt)
    use Illuminate\Database\Eloquent\SoftDeletes;
@endif

class {{$modelBaseName}} extends Model
{
   
   @if($hasDeletedAt)
        use SoftDeletes;
    @endif
}
