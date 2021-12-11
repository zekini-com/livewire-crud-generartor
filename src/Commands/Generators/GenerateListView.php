<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class GenerateListView extends BaseGenerator
{

    protected $classType = 'list-view';

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:views:list {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a index for the model';

    

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
       $filename = $path.'/list-'.strtolower($this->className).'.blade.php';
      
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
        $resource = strtolower($this->getClassName());
        return [
            'vissibleColumns'=> $this->getColumnDetailsWithId(),
            'modelName'=> ucfirst($this->getClassName()),
            'resource'=> $resource,
            'createResourceRoute'=> "url('admin/$resource/create')",
            'hasDeletedAt'=> $this->hasColumn('deleted_at')
        ];
    }
    



  
}