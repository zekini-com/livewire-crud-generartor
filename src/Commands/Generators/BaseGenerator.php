<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Zekini\CrudGenerator\Traits\ColumnTrait;
use Zekini\CrudGenerator\Traits\HasRelations;

abstract class BaseGenerator extends Command
{

    use ColumnTrait, HasRelations;

    protected $hidden = true;

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

    /**
     * Get Column Faker Map
     *
     * @return void
     */
    protected function getColumnFakerMap()
    {
       $columns = $this->getColumnDetails();
       return $columns->map(function($colArr){
           return [
               'name'=> $colArr['name'],
               'faker'=> $this->decideFaker($colArr['type'], $colArr['name'])
           ];
       });
        
    }

    
    /**
     * Decide what faker to user
     *
     * @param  string $colName
     * @return string
     */
    protected function decideFaker($type, $name)
    {
        if(Str::isRelation($name)) return "\App\Models\\".ucfirst(Str::relationName($name))."::factory()->create()->id";
        if ($name == 'name') return '$this->faker->name()';
        if ($name == 'email') return '$this->faker->unique()->safeEmail()';
        if ($name == 'image' || $name == 'file') return "[\Illuminate\Http\UploadedFile::fake()->image('file.jpg')]";
        if ($name == preg_match('/phone/', $name)) return '$this->faker->phoneNumber()';

        switch($type){
            case 'string':
                return '$this->faker->word()';
            break;
            case 'boolean':
                return '$this->faker->bolean()';
            break;
            case 'text':
                return '$this->faker->sentence()';
            break;
            default: 
                return '$this->faker->word()';
        }
    }
    

    

  
}