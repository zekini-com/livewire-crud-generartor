<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateModel extends BaseGenerator
{

    protected $classType = 'model';
    
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
    protected $signature = 'admin:generate:model {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates model';

    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'Models\\';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Model Class');

        $this->className = $this->getClassName();

        $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

        $templateContent = $this->replaceContent();

        @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777);
        $filename = $path.'/'.$this->className.'.php';
       
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
            'relations'=> $this->getRelations() ?? []
        ];
    }

   
    

  
}