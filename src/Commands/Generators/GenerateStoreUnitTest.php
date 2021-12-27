<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class GenerateStoreUnitTest extends BaseGenerator
{

    protected $classType = 'store-test';

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:test:store {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Unit testing';

    
      /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Tests\Unit\\';
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
       $this->classNameKebab = Str::kebab($this->className);

       $this->testBaseName = $this->className.'StoreTest';

       $this->namespace = $this->getDefaultNamespace().'\\'.ucfirst($this->className);

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777);
       $filename = $path.'/'.$this->testBaseName.'.php';
      
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
            'modelBaseName' => ucfirst($this->getClassName()),
            'adminModel'=> '\\'.config('zekini-admin.providers.zekini_admins.model'),
            'resource'=> $this->getClassName(),
            'tableName'=> $this->argument('table'),
            'columnFakerMappings'=> $this->getColumnFakerMap(),
            'viewName'=> 'create-'.$this->classNameKebab,
            'modelDotNotation'=> Str::singular($this->argument('table'))
         
        ];
    }
    



  
}