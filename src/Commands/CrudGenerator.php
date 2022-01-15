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
    protected $signature = 'admin:crud:generate {table : table to generate crud for } {--user : When added the crud is generated for a user model}';

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
            $this->call($command, $this->getOptionsArgument());
        }


        return Command::SUCCESS;
    }


    protected function getOptionsArgument()
    {
        $array = ['table'=> $this->argument('table')];
        if($this->option('user')) {
            $array['--user'] = $this->option('user');
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
            'admin:generate:model',

            'admin:generate:route',
            'admin:generate:form',

            // livewire views
            'admin:generate:views:index',
            'admin:generate:views:list',
            'admin:generate:views:edit',
            'admin:generate:views:create',

            // livewire components
            'admin:generate:component:datatable',
            'admin:generate:component:index',

            //controller
            //'admin:generate:controller',

            'admin:generate:permission',
            
            // 'admin:generate:test',
            'admin:generate:factory'
        ];
    }

 
}