@php echo "<?php";
@endphp

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
@if($hasDeletedAt)
    use Illuminate\Database\Eloquent\SoftDeletes;
@endif
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zekini\CrudGenerator\Traits\HasModelRelations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class {{$modelBaseName}} extends Model 
{

    use 
    @if($hasDeletedAt)
         SoftDeletes,
     @endif
     HasFactory, HasModelRelations;
     @if($modelBaseName !== 'ActivityLog')
     use LogsActivity;
     @endif
    
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


    @if($modelBaseName !== 'ActivityLog')

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    @endif

    @if($modelBaseName == 'ActivityLog')
        public function causer(){
            return $this->morphTo();
        }

        public function subject(){
            return $this->morphTo();
        }
    @endif

    @if($isUser) 
        public function audits()
        {
            return $this->morphMany(\App\Models\ActivityLog::class, 'causer');
        }
    @endif

    
   

   

    // Relationships start here
    @if(count($relations)> 0)
    @foreach($relations as $index=>$relation)

        
       
        @php
            $relationName = Str::getRelationship($relation);
        @endphp
        public function {{$relationName}}()
        {
        
            return $this->{{Str::camel($relation['name'])}}({{ucfirst(Str::singular($relation['table']))}}::class
            @if(isset($relation['pivot']))
            , "{{$relation['pivot']}}"
            @endif
            @if(isset($relation['foreign_pivot_key']))
            , "{{$relation['foreign_pivot_key']}}"
            @endif
            @if(isset($relation['related_pivot_key']))
            , "{{$relation['related_pivot_key']}}"
            @endif
            );
        }

    @endforeach
    @endif
   
}
