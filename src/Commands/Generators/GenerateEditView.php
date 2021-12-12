<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class GenerateEditView extends BaseGenerator
{

    protected $classType = 'edit-view';

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:views:edit {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a edit page  for the model';

    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = resource_path('views/livewire'), 0777);
       $filename = $path.'/edit-'.strtolower($this->className).'.blade.php';
      
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