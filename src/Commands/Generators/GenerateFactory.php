<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class GenerateFactory extends BaseGenerator
{

    protected $classType = 'factory';

    protected $factoryBaseName;

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:factory {table : table to generate crud for } {--user : When added the crud is generated for a user model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Factory';

    
      /**
     * Get the default namespace for the class.
     * 
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Database\Factories\\';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();

       $this->factoryBaseName = $this->className.'Factory';

       $this->namespace = $this->getDefaultNamespace();

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = strtolower($this->getPathFromNamespace($this->namespace)), 0777);
       $filename = $path.'/'.$this->factoryBaseName.'.php';
      
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
            'factoryBaseName' => $this->factoryBaseName,
            'factoryNamespace' => rtrim($this->namespace, '\\'),
            'fakerAttributes'=> $this->getColumnFakerMap(),
            'factoryModelNamespace'=> "App\Models\\".$this->className,
            'modelBaseName'=> ucfirst($this->className)
        ];
    }

    
    
    

  
}