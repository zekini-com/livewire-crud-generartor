<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateListComponent extends BaseGenerator
{

    protected $classType = "component-list";

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:component:list {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a livewire list component for the model';


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'Http\Livewire\\';
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating List Component Class');
       
       //publish any vendor files to where they belong
       $this->className = $this->getClassName();

       $this->componentName = 'List'.$this->className;

       $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

       $templateContent = $this->replaceContent();

       @$this->files->makeDirectory($path = $this->getPathFromNamespace($this->namespace), 0777);
       $filename = $path.'/'.$this->componentName.'.php';
      
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
        
    // TODO
    // I need to check when the relationship is a belongsto
    $pivots = $this->belongsToConfiguration()->filter(function($item){
        return !empty($item['pivot']) && isset($item['pivot']);
    });
        
        return [
    
            'controllerNamespace' => rtrim($this->namespace, '\\'),
            'modelBaseName' => $this->className,
            'modelVariableName' => strtolower($this->className),
            'modelDotNotation' => Str::singular($this->argument('table')),
            'resource'=> strtolower($this->className),
            'modelFullName'=> "App\Models\\".$this->className,
            'vissibleColumns'=> $this->getColumnDetails(),
            'relations'=>$this->belongsToConfiguration(),
            'pivots'=> $pivots,
            'tableTitleMap'=> $this->getRecordTitleTableMap(),
            'canBeTrashed'=> $this->hasColumn('deleted_at'),
            'hasFile'=> $this->hasColumn('image') || $this->hasColumn('file')
        ];
    }
    

  
}