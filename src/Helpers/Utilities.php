<?php
namespace Zekini\CrudGenerator\Helpers;

use Illuminate\Support\Facades\File;

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

        File::put($pathToFile, str_replace($find, $replaceWith, $content));
    }
}