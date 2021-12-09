<?php
namespace Zekini\CrudGenerator\Traits;

use Illuminate\Support\Facades\Schema;

trait ColumnTrait
{

    protected $dontShow = [
        'id',
        'created_at',
        'deleted_at',
        'updated_at',
        'remember_token'
    ];
    
    /**
     * Gets column details of table
     *
     * @return Collection
     */
    public function getColumnDetails()
    {
        $tableName = $this->argument('table');
        $blackList = $this->dontShow;

        $columns = collect(Schema::getColumnListing($tableName));
        $columns = $columns->reject(function($col) use($blackList){
            return in_array($col, $blackList);
        })
        ->map(function($col) use($tableName){
            return [
                'name'=> $col, 
                'type'=> Schema::getColumnType($tableName, $col),
                'required'=> boolval(Schema::getConnection()->getDoctrineColumn($tableName, $col)->getNotnull())
            ];
        });

        return $columns;
    }


     /**
     * Gets column details of table
     *
     * @return Collection
     */
    public function getColumnDetailsWithId()
    {
        $tableName = $this->argument('table');
        $blackList = $this->dontShow;
        unset($blackList['id']);
        
        $columns = collect(Schema::getColumnListing($tableName));
        $columns = $columns->reject(function($col) use($blackList){
            return in_array($col, $blackList);
        })
        ->map(function($col) use($tableName){
            return [
                'name'=> $col, 
                'type'=> Schema::getColumnType($tableName, $col),
                'required'=> boolval(Schema::getConnection()->getDoctrineColumn($tableName, $col)->getNotnull())
            ];
        });

        return $columns;
    }
}