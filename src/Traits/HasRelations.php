<?php
namespace Zekini\CrudGenerator\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait HasRelations
{
    
   
    
    /**
     * Retrieve relationships attached to a table
     *
     * @return array
     */
    protected function getRelations()
    {
        $tableName = $this->argument('table');
        $relations = config('zekini-admin.relationships.'.$tableName);
        
        return $relations ?? [];
    }

    
    /**
     * belongsToConfiguration
     *
     * @return Collection
     */
    protected function belongsToConfiguration()
    {
        $relations = collect($this->getRelations());
        $belongsTo = $relations->filter(function($relation){
            return strpos($relation['name'], 'belongs_to') !== false;
        });
       
        return $belongsTo->map(function($relation){
            return [ 
                'column'=>Str::singular($relation['table']).'_id',
                'table'=> $relation['table'],
                'pivot'=> $relation['pivot'] ?? ''
            ];

        });
    }

    
    /**
     * Extracts the record and title to a keypair value
     *
     * @return array
     */
    protected function getRecordTitleTableMap()
    {
        $relations = collect($this->getRelations());

        $map = [];

        foreach($relations as $relation){
            $map[$relation['table']] = $relation['record_title'];
        }

        return $map;
    }
}