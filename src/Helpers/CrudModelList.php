<?php
namespace Zekini\CrudGenerator\Helpers;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;


class CrudModelList
{
    
    /**
     * Gets a list of items
     *
     * @param  string $model
     * @param  boolean $isTrashed
     * @param  boolean $canBeTrashed
     * @return Collection
     */
    public static function getList($model, $isTrashed, $canBeTrashed, $searchWord='')
    {
        
        $relations = Utilities::getRelations($model);

       
        $list =  ($isTrashed && $canBeTrashed) ? 
            $model::onlyTrashed()->with($relations)->search($searchWord)->paginate(10) : 
            $model::with($relations)->search($searchWord)->paginate(10);
      
       return $list;
    }
    
   


}