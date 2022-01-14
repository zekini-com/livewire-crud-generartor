<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class GenerateForm extends BaseGenerator
{

    protected $classType = 'form';

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:form {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a unique form for model';

    

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

       @$this->files->makeDirectory($path = resource_path('views/livewire/'.Str::plural($this->classNameKebab).DIRECTORY_SEPARATOR.'partials'), 0777, true);
       $filename = $path.DIRECTORY_SEPARATOR.'form.blade.php';
      
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
      
       $pivots = $this->belongsToConfiguration()->filter(function($item){
            return !empty($item['pivot']) && isset($item['pivot']);
        });
       
        return [
            'vissibleColumns'=> $this->getColumnDetails(),
            'relations'=>  $this->getRelations(),
            'belongsTo'=> $this->belongsToConfiguration()->pluck('column')->toArray(),
            'recordTitleMap'=> $this->getRecordTitleTableMap(),
            'pivots'=> $pivots ?? []
        ];
    }
    



  
}