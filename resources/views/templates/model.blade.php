@php echo "<?php";
@endphp

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
@if($hasDeletedAt)
    use Illuminate\Database\Eloquent\SoftDeletes;
@endif
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Exceptions\AuditingException;
use OwenIt\Auditing\Resolvers\UserResolver;

class {{$modelBaseName}} extends Model implements Auditable
{

    use 
    @if($hasDeletedAt)
         SoftDeletes,
     @endif
     AuditableTrait;
    
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


    /**
     * Resolve the User.
     *
     * @throws AuditingException
     *
     * @return mixed|null
     */
    protected function resolveUser()
    {
        $userResolver = \Zekini\CrudGenerator\Resolvers\ZekiniAdminResolver::class;

        if (is_subclass_of($userResolver, UserResolver::class)) {
            return call_user_func([$userResolver, 'resolve']);
        }

        throw new AuditingException('Invalid UserResolver implementation');
    }
   
}
