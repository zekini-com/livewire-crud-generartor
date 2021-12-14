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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zekini\CrudGenerator\Traits\HasModelRelations;

class {{$modelBaseName}} extends Model implements Auditable
{

    use 
    @if($hasDeletedAt)
         SoftDeletes,
     @endif
     HasFactory, HasModelRelations,
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

    // Relationships start here
    @if(count($relations)> 0)
    @foreach($relations as $index=>$relation)
        @if(strpos($relation['table'], 'has'))
        public function {{$relation['table']}}()
        {
        
            return $this->{{Str::camel($relation['name'])}}({{ucfirst(Str::singular($relation['table']))}}::class);
        }
        @else
        public function {{Str::singular($relation['table'])}}()
        {
        
            return $this->{{Str::camel($relation['name'])}}({{ucfirst(Str::singular($relation['table']))}}::class);
        }
        @endif
    @endforeach
    @endif
   
}
