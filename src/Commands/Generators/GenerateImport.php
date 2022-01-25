<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateImport extends BaseGenerator
{

    protected $classType = 'import';

    protected $model;
    
    /**
     * class name
     *
     * @var string
     */
    protected $className;
    
    /**
     * class namespace
     *
     * @var string
     */
    protected $namespace;

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:import {table : table to generate crud for } {--user : When added the crud is generated for a user model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates import';

    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'Imports\\';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Imports Class');

        $this->className = $this->getClassName().'';

        $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

        $templateContent = $this->replaceContent();

        @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777);

        $filename = $path.DIRECTORY_SEPARATOR.Str::plural($this->className).'Import.php';
       
        $this->files->put($filename, $templateContent);
       
        return Command::SUCCESS;
    }


     /**
     * Get view data
     *
     * @return array
     */
    protected function getViewData()
    {
       
        return [
            'modelBaseName' => ucfirst($this->className),
            'hasDeletedAt'=> $this->hasColumn('deleted_at'),
            'vissibleColumns'=> $this->getColumnDetails(),
            'relations'=> $this->getRelations() ?? [],
            'isUser'=> $this->option('user')
        ];
    }

   
    

  
}