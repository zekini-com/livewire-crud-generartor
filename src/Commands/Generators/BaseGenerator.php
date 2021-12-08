<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Zekini\CrudGenerator\Traits\ColumnTrait;

abstract class BaseGenerator extends Command
{

    use ColumnTrait;

    protected $files;
    
    /**
     * __construct
     *
     * @param  mixed $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }
    
    /**
     * rootNamespace
     *
     * @return string
     */
    public function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }
    
    /**
     * Get the name of the class
     *
     * @return string
     */
    protected function getClassName()
    {
        return rtrim(Str::studly($this->argument('table')), 's');    
    }

    /**
     * getTemplate
     *
     * @return void
     */
    protected function getTemplateUrl($file)
    {
        return __DIR__.'../../../../templates/'.$file.'.blade.php';
    }

    
    /**
     * Get the path of a file from the namespace
     *
     * @param  string $namespace
     * @return string
     */
    protected function getPathFromNamespace($namespace)
    {
        // replace the slashes in the namespace
        $namespace = str_replace('\\','/', trim($namespace, '\\'));
        $namespace = preg_replace('/^App/', 'app', $namespace);
        return $namespace;
    }


     /**
     * Replaces the content in the file
     *
     * @return string
     */
    protected function replaceContent()
    {
       
        $variables = $this->getViewData();

        $view = "zekini/livewire-crud-generator::templates.".$this->classType;

        return view($view, $variables)->render();

    }

    

  
}