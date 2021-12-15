<?php
namespace Zekini\CrudGenerator\Helpers;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
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
    public static function getList($model, $isTrashed, $canBeTrashed, $searchWord='')
    {
        
        $relations = self::getRelations($model);
       
        $list =  ($isTrashed && $canBeTrashed) ? 
            $model::onlyTrashed()->with($relations)->search(self::getSearchKey($model), $searchWord)->paginate(10) : 
            $model::with($relations)->search(self::getSearchKey($model), $searchWord)->paginate(10);
      
       return $list;
    }
    
    /**
     * Gets the key to use for searching
     *
     * @return string
     */
    public static function getSearchKey($model)
    {
        $table =   Str::snake(Str::pluralStudly(class_basename($model)));
        $searchKey = @config('zekini-admin.searck_keys.'.$table);
        $dontShow = [
            'id',
            'created_at',
            'deleted_at',
            'updated_at',
            'remember_token'
        ];
        if (! $searchKey) {
            $searchKey = collect(Schema::getColumnListing($table))->first(function($col) use($dontShow){
                return !in_array($col, $dontShow);
            });

        }
        return $searchKey;
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
           
            $relationName =  strpos($relation['name'], 'belong') === false ? $relation['table'] : Str::singular($relation['table']);
           
            return $relationName;
        })->toArray();
    }
}