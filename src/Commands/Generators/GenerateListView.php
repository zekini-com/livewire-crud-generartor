<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

        // $colMap = $this->getRecordTitleTableMap();

        // $relations = $this->belongsToConfiguration()->map(function($relation) use($colMap){
        //     return Str::singular($relation['table']).'.'.$colMap[$relation['table']];
        // });
       
        // $columns  = $this->getColumnWithDates()->filter(function($col){
        //     return ! Str::isRelation($col['name']);
        // })->map(function($col){
        //     return $col['name'];
        // });

        // $columns = $columns->merge($relations)->toArray();

        return [
            'componentName'=> strtolower('list-'.$this->className),
            'resource'=> strtolower($this->className),
            'createView'=> strtolower('create-'.$this->className)
        ];
   
    }
    



  
}