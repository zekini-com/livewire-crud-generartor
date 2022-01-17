<?php
namespace Zekini\CrudGenerator\Commands\Generators\Component;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Zekini\CrudGenerator\Commands\Generators\BaseGenerator;

class GenerateDatatableComponent extends BaseGenerator
{

    protected $classType = "component-datatable";

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:component:datatable 
                            {table : table to generate crud for } 
                            {--user : When added the crud is generated for a user model}
                            {--readonly : The datatable is read only no create and edit buttons}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a datatable for a component';


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
        $this->info('Generating Datatable Component Class');
       
       $this->className = $this->getClassName();

       $templateContent = $this->replaceContent();

       $path = $this->getLivewireComponentDir('Datatable');
       
       @$this->files->makeDirectory($path, 0777, true, true);

       $filename = $path.DIRECTORY_SEPARATOR.Str::plural($this->className).'Table.php';
      
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
            'relations'=>$this->belongsToConfiguration() ?? [],
            'pivots'=> $pivots,
            'tableTitleMap'=> $this->getRecordTitleTableMap(),
            'canBeTrashed'=> $this->hasColumn('deleted_at'),
            'hasFile'=> $this->hasColumn('image') || $this->hasColumn('file'),
            'isReadonly'=> $this->option('readonly')
        ];
    }
    

  
}