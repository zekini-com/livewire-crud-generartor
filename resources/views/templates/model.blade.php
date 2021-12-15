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
     * Creates the query builder query needed for a relational search
     *
     * @param  string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if(empty($search)){
            return $query;
        }
      
        $query->where('{{\Zekini\CrudGenerator\Helpers\Utilities::getSearchKey($modelBaseName)}}', 'like', '%'.$search.'%')
        @foreach(\Zekini\CrudGenerator\Helpers\Utilities::getRelations($modelBaseName) as $relation)
        ->OrWhereHas('{{$relation}}', function($rQuery) use ($search){
            $rQuery->where('{{\Zekini\CrudGenerator\Helpers\Utilities::getSearchKey($relation)}}', 'like', '%'.$search.'%');
        })
        @endforeach
        ;
    }

   




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
       
        @php
            $relationName = strpos($relation['name'], 'belong') === false ? $relation['table'] : Str::singular($relation['table']);
        @endphp
        public function {{$relationName}}()
        {
        
            return $this->{{Str::camel($relation['name'])}}({{ucfirst(Str::singular($relation['table']))}}::class);
        }

    @endforeach
    @endif
   
}
