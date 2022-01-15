<?php
namespace Zekini\CrudGenerator\Commands\Generators\View;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Zekini\CrudGenerator\Commands\Generators\BaseGenerator;

class GenerateIndexView extends BaseGenerator
{

    protected $classType = 'index-view';

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:views:index {table : table to generate crud for } {--user : When added the crud is generated for a user model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an index view for the model';

    

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

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = resource_path('views/livewire/'.Str::plural($this->classNameKebab)), 0777);
       $filename = $path.DIRECTORY_SEPARATOR.'index.blade.php';
      
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
            'resource'=> Str::plural($this->getClassName()),
            'modelVariableName'=> Str::camel($this->getClassName()),
            'componentName'=> $this->getComponentName(),
            'viewName'=> Str::plural($this->classNameKebab),
            'wireEdit'=> Str::camel($this->getClassName()).'EditModal',
            'wireCreate'=> Str::camel($this->getClassName()).'CreateModal',
        ];
    }

    protected function getComponentName()
    {
        $name = strtolower(Str::plural(Str::kebab($this->getClassName())));
        return "$name.datatable.$name-table";
    }
    



  
}