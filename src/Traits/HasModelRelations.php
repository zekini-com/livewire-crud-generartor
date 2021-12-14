<?php
namespace Zekini\CrudGenerator\Traits;

use Illuminate\Support\Str;


trait HasModelRelations
{

     /**
     * Get Model Relations array
     *
     * @return array
     */
    public static function getModelRelationsArray()
    {
        $tableName = Str::snake(Str::pluralStudly(class_basename($this)));
        $relations = config('zekini-admin.relationships.'.$tableName);
        
        return $relations;
    }
}