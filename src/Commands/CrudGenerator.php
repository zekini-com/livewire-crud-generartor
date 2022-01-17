<?php
namespace Zekini\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Zekini\CrudGenerator\Helpers\Utilities;

class CrudGenerator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:crud:generate 
                            {table : table to generate crud for } 
                            {--user : When added the crud is generated for a user model} 
                            {--exclude=* : An array of classes not generate}
                            {--readonly : The datatable is read only no create and edit buttons}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates crud for a particular table in the db given the table name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      

        // TODOS
        //check if table exists
        $this->info('Checking if table exists');

        $tableName = $this->argument('table');
     
        if(! Schema::hasTable($tableName)) {
            $this->error('Cannot find table. exiting');
            return Command::FAILURE;
        }

        // Get all table columns and attributes
        $columns = Schema::getColumnListing($tableName);
        $generators = $this->getGenerators();
        
        foreach($generators as $index=>$command) {
            if(in_array($index, $this->option('exclude'))) continue;
            $this->call($command, $this->getOptionsArgument($command));
        }


        return Command::SUCCESS;
    }


    protected function getOptionsArgument($command)
    {
        $array = ['table'=> $this->argument('table')];
        if($this->option('user')) {
            $array['--user'] = $this->option('user');
        }

        $readonlyCommands = [
            'admin:generate:component:datatable',
            'component-index'=>'admin:generate:component:index'
        ];
       

        if($this->option('readonly') && in_array($command, $readonlyCommands)){
          
            $array['--readonly'] = $this->option('readonly');
        }

        return $array;
    }

    
    /**
     * Get Generators
     *
     * @return array
     */
    protected function getGenerators()
    {
        return [
            'model'=> 'admin:generate:model',

            'route'=>'admin:generate:route',
            'form'=>'admin:generate:form',

            // livewire views
            'view-index'=>'admin:generate:views:index',
            'view-list'=>'admin:generate:views:list',
            'view-edit'=>'admin:generate:views:edit',
            'view-create'=>'admin:generate:views:create',

            // livewire components
            'component-datatable'=>'admin:generate:component:datatable',
            'component-index'=>'admin:generate:component:index',

            //controller
            //'admin:generate:controller',

            'permission'=>'admin:generate:permission',
            
            'test-store'=>'admin:generate:test:store',
            'test-update'=>'admin:generate:test:update',
            'test-list'=>'admin:generate:test:list',

            'factory'=>'admin:generate:factory'
        ];
    }

 
}