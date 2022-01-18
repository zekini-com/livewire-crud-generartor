<?php
namespace Zekini\CrudGenerator\Mixin;

use Illuminate\Support\Str;


class StrMixin
{
    
  
    public function isRelation (){

        return function($str) {
            return strpos($str, '_id') !==false;
        };
       
    }

    public function getRelationship()
    {
        return function($arr){
            return in_array($arr['name'], ['belongs_to_many', 'has_many']) ? $arr['table'] : Str::singular($arr['table']);
        };
    }
    
   
    public function relationName (){

        return function($str){
            return str_replace('_id', '', $str);
        };
       
    }

   
    public function likelyFile (){

        return function($str){
            return preg_match('/image/', $str) || preg_match('/file/', $str);
        };
       
    }
}