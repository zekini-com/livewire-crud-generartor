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
    protected $signature = 'admin:generate:views:index {table}';

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
            'modelVariableName'=> strtolower($this->getClassName()),
            'componentName'=> $this->getComponentName(),
            'wireEdit'=> strtolower($this->getClassName()).'EditModal',
            'wireCreate'=> strtolower($this->getClassName()).'CreateModal',
        ];
    }

    protected function getComponentName()
    {
        $name = strtolower(Str::plural($this->getClassName()));
        return "$name.datatable.$name-table";
    }
    



  
}