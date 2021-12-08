<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Zekini\CrudGenerator\Traits\CreatesSidebar;

class GenerateRoutes extends BaseGenerator
{
    use CreatesSidebar;

    protected $classType = "routes";

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:route {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates routes';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating routes for crud');
       
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();

       $this->resourceController = $this->className.'Controller';

       $templateContent = $this->appendContent();

       $filename = base_path('routes/web.php');
      
       $this->files->append($filename, $templateContent);

       $this->makeSideBar();
        
        return Command::SUCCESS;
    }

     /**
     * Append the content in the file
     *
     * @return string
     */
    protected function appendContent()
    {
       
        $variables = $this->getViewData();

        $view = "zekini/livewire-crud-generator::templates.".$this->classType;

        return view($view, $variables)->render();

    }
    


    
    /**
     * Get view data
     *
     * @return array
     */
    protected function getViewData()
    {
        return [
            'resourceController' => $this->resourceController,
            'resource'=> strtolower($this->className),
            'modelVariableName'=>strtolower($this->className) 
        ];
    }
    

  
}