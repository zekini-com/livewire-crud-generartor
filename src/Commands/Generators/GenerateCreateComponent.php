<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateCreateComponent extends BaseGenerator
{

    protected $classType = "component-create";

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:component:create {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a livewire create component for the model';


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'Http\Livewire\\';
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating Create Component Class');
       
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();

       $this->componentName = 'Create'.$this->className;

       $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777);
       $filename = $path.'/'.$this->componentName.'.php';
      
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
    
            'controllerNamespace' => rtrim($this->namespace, '\\'),
            'modelBaseName' => $this->className,
            'modelVariableName' => strtolower($this->className),
            'modelDotNotation' => strtolower($this->className),
            'resource'=> strtolower($this->className),
            'modelFullName'=> "App\Models\\".$this->className,
            'vissibleColumns'=> $this->getColumnDetails()
        ];
    }
    

  
}