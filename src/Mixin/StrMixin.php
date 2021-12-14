<?php
namespace Zekini\CrudGenerator\Mixin;


class StrMixin
{
    
    /**
     * Checks a string can be considered as a column name
     *
     * @param  string $str
     * @return bool
     */
    public function isRelation (){

        return function($str) {
            return strpos($str, '_id') !==false;
        };
       
    }
    
    /**
     * Guesses the name of the relation from the column name
     *
     * @param  string $str
     * @return string
     */
    public function relationName (){

        return function($str){
            return str_replace('_id', '', $str);
        };
       
    }
}