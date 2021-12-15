<?php
namespace Zekini\CrudGenerator\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Utilities
{

    
    /**
     * Finds a content in a file and replaces it
     *
     * @param  string $pathToFile
     * @param  string $find
     * @param  string $replaceWith
     * @return void
     */
    public static function strReplaceInFile($pathToFile, $find, $replaceWith)
    {
        $content = File::get($pathToFile);
        
        // only replace when the replacement string is not there
        if (strpos($content, $replaceWith) == false ){
            File::put($pathToFile, str_replace($find, $replaceWith, $content));
        }
        
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


     /**
     * Gets the key to use for searching
     *
     * @return string
     */
    public static function getSearchKey($model)
    {
        $table =   Str::snake(Str::pluralStudly(class_basename($model)));
        $searchKey = @config('zekini-admin.search_keys.'.$table);
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
}