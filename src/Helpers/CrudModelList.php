<?php
namespace Zekini\CrudGenerator\Helpers;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;


class CrudModelList
{
    
    /**
     * getList
     *
     * @param  mixed $model
     * @param  mixed $isTrashed
     * @param  mixed $canBeTrashed
     * @return Collection
     */
    public static function getList($model, $isTrashed, $canBeTrashed)
    {
        
        $relations = self::getRelations($model);

        $list =  ($isTrashed && $canBeTrashed) ? $model::onlyTrashed()->with($relations)->get() : $model::with($relations)->get();
      
       return $list;
    }

    
    /**
     * getRelations
     *
     * @param  mixed $model
     * @return void
     */
    public static function getRelations($model)
    {
        $table =   Str::snake(Str::pluralStudly(class_basename($model)));
        return collect(config('zekini-admin.relationships.'.$table))->map(function($relation){
            $relationName =  strpos($relation['name'], 'has') ? $relation['table'] : Str::singular($relation['table']);
            return $relationName;
        })->toArray();
    }
}