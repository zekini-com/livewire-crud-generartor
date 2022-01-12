<?php
namespace Zekini\CrudGenerator\Mixin;


class StrMixin
{
    
  
    public function isRelation (){

        return function($str) {
            return strpos($str, '_id') !==false;
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