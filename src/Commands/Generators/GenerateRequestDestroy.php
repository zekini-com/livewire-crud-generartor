<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateRequestDestroy extends BaseGenerator
{

    protected $classType = "destroy-request";

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:request:destroy {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Delete Request ';


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $className = ucfirst($this->className);
        return $rootNamespace."Http\Requests\Admin\\$className\\";
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating Destroy Request Class');
       
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();

       $this->requestClass = "Destroy".ucfirst($this->className);

       $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777, true);
   
       $filename = $path.'/'.$this->requestClass.'.php';
      
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
            'modelDotNotation' => strtolower($this->className),
            'modelVariableName'=> strtolower($this->className)
        ];
    }
    

  
}