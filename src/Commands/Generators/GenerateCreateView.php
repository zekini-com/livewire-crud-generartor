<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class GenerateCreateView extends BaseGenerator
{

    protected $classType = 'create-view';

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:views:create {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a create page  for the model';

    

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

       @$this->files->makeDirectory($path = resource_path('views/livewire'), 0777);
       $filename = $path.'/create-'.$this->classNameKebab.'.blade.php';
      
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
            'vissibleColumns'=> $this->getColumnDetails(),
            'modelVariableName'=> strtolower($this->getClassName())
        ];
    }
    



  
}