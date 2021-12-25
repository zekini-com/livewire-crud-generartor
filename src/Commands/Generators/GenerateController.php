<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateController extends BaseGenerator
{

    protected $classType = "controller";

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:controller {table}';

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
        return $rootNamespace.'Http\Controllers\Admin\\';
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating Controller Class');
       
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();
       $this->classNameKebab = Str::kebab($this->className);

       $this->controllerNamespace = $this->className.'Controller';

       $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777);
       $filename = $path.'/'.$this->controllerNamespace.'.php';
      
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
            'controllerBaseName' => $this->controllerNamespace,
            'controllerNamespace' => rtrim($this->namespace, '\\'),
            'modelBaseName' => $this->className,
            'modelPlural' => $this->className.'s',
            'modelVariableName' => strtolower($this->className),
            'modelDotNotation' => Str::singular($this->argument('table')),
            'modelWithNamespaceFromDefault' => rtrim($this->namespace, '\\'),
            'resource'=> strtolower($this->className),
            'modelFullName'=> "App\Models\\".$this->className,
            'viewName'=> 'livewire.list-'.$this->classNameKebab
        ];
    }
    

  
}