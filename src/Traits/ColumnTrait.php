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
    public function getColumnWithDates()
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
    public function getColumnDetailsWithRelations()
    {
       $columns = $this->getColumnDetails();
      
       $belongsTo = $this->belongsToConfiguration()->pluck('column')->toArray();
       
       return $belongsTo;
    }

    
    /**
     * The model contains a particular column
     *
     * @param  string $col
     * @return boolean
     */
    public function hasColumn($col)
    {
        return Schema::hasColumn($this->argument('table'), $col);
    }

}