<?php
namespace Zekini\CrudGenerator\Commands\Generators\Test;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Zekini\CrudGenerator\Commands\Generators\BaseGenerator;

class GenerateDatatableTest extends BaseGenerator
{

    protected $classType = 'datatable-test';

    protected $testBaseName;

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:test:datatable 
                            {table : table to generate crud for } 
                            {--user : When added the crud is generated for a user model}
                            {--readonly : The datatable is read only no create and edit buttons}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Unit testing';

    
      /**
     * Get the default namespace for the class.
     *
     * @return string
     */
    protected function getDefaultNamespace()
    {
        return 'Tests\Unit';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        if($this->option('readonly')) {
            $this->info('Skipping generating datatable test for readonly classes');
            return Command::SUCCESS;
        }

       //publish any vendor files to where they belong
       $this->className = $this->getClassName();
       $this->classNameKebab = Str::kebab($this->className);

       $this->testBaseName = $this->className.'DatatableTest';

       $this->namespace = $this->getDefaultNamespace().DIRECTORY_SEPARATOR.ucfirst($this->className);
      
    
       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777, true, true);
      
       $filename = $path.DIRECTORY_SEPARATOR.$this->testBaseName.'.php';
       
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